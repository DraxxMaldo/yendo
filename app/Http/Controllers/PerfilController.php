<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PerfilPersona;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ActualizarPerfilPropioRequest;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\VerificarCodigoPasswordRequest;
use App\Mail\CodigoRegistroMail;
use App\Mail\NuevaContrasenaMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PerfilController extends Controller
{
    public function index()
    {
        $usuarioCredencial = Auth::user();

        // Cargamos la relación del rol directamente desde el modelo de credenciales
        $usuarioCredencial->load('rol');

        $perfilPersona = PerfilPersona::where('id_usuario', $usuarioCredencial->id_usuario)->first();

        // Consultar la última solicitud de afiliación del usuario
        $solicitudAfiliacion = \App\Models\SolicitudAfiliacion::where('id_usuario', $usuarioCredencial->id_usuario)
            ->latest()
            ->first();

        $estadoSolicitud = $solicitudAfiliacion ? $solicitudAfiliacion->estado : null; // 'pendiente', 'aprobada', 'rechazada' o null

        // Obtenemos el nombre del rol dinámicamente de la base de datos (con respaldo por si acaso)
        $nombreRol = $usuarioCredencial->rol ? $usuarioCredencial->rol->nombre_rol : 'Usuario';

        $urlImagen = $perfilPersona && $perfilPersona->imagen
            ? asset('storage/' . $perfilPersona->imagen)
            : 'https://ui-avatars.com/api/?name=' . urlencode(($perfilPersona ? $perfilPersona->nombres : 'Usuario') . ' ' . ($perfilPersona ? $perfilPersona->apellidos : '')) . '&background=EEEBDA&color=282B4A&size=180&bold=true';

        $datosUsuario = [
            'id_usuario'       => $usuarioCredencial->id_usuario,
            'correo'           => $usuarioCredencial->correo_electronico,
            'nombres'          => $perfilPersona ? $perfilPersona->nombres : '',
            'apellidos'        => $perfilPersona ? $perfilPersona->apellidos : '',
            'telefono'         => $perfilPersona ? $perfilPersona->telefono : '',
            'nombre_negocio'   => $perfilPersona ? $perfilPersona->nombre_negocio : '',
            'imagen'           => $urlImagen,
            'rol'              => $nombreRol, // <--- Aquí se inyecta el nombre real del rol
            'estado_solicitud' => $estadoSolicitud,
        ];

        return view('menu.perfil.perfil', compact('datosUsuario'));
    }

    public function actualizarFoto(Request $request)
    {
        $request->validate([
            'imagen' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'imagen.image' => 'El archivo seleccionado debe ser una imagen válida.',
            'imagen.mimes' => 'Formatos permitidos: jpeg, png, jpg, webp.',
            'imagen.max'   => 'La imagen no debe pesar más de 2MB.',
        ]);

        try {
            $usuarioCredencial = Auth::user();
            $perfilPersona = PerfilPersona::where('id_usuario', $usuarioCredencial->id_usuario)->first();

            if ($request->hasFile('imagen')) {
                if ($perfilPersona->imagen && Storage::disk('public')->exists($perfilPersona->imagen)) {
                    Storage::disk('public')->delete($perfilPersona->imagen);
                }

                $rutaImagen = $request->file('imagen')->store('perfiles', 'public');

                $perfilPersona->update([
                    'imagen' => $rutaImagen
                ]);

                Bitacora::registrar(Bitacora::ACTUALIZADO, 'Perfil de Usuario', $usuarioCredencial->id_usuario, "El usuario actualizó su fotografía de perfil.");

                return back()->with('success', '¡Fotografía de perfil actualizada correctamente!');
            }

            return back()->with('error', 'No se ha seleccionado ninguna imagen.');

        } catch (\Throwable $e) {
            return back()->with('error', 'Error al actualizar la foto: ' . $e->getMessage());
        }
    }

    public function actualizarInformacion(ActualizarPerfilPropioRequest $request)
    {
        $datos = $request->validated();

        $usuarioCredencial = Auth::user();

        // Si el usuario es rol básico (3), evitamos que modifique el nombre del negocio
        if ($usuarioCredencial->id_rol == 3) {
            $datos['nombre_negocio'] = null; // O mantenemos el valor actual que tenga
        }

        $perfilPersona = PerfilPersona::where('id_usuario', $usuarioCredencial->id_usuario)->first();

        if ($perfilPersona) {
            $perfilPersona->update([
                'nombres'        => $datos['nombres'],
                'apellidos'      => $datos['apellidos'],
                'telefono'       => $datos['telefono'],
                'nombre_negocio' => $usuarioCredencial->id_rol == 3 ? $perfilPersona->nombre_negocio : $datos['nombre_negocio'],
            ]);

            Bitacora::registrar(Bitacora::ACTUALIZADO, 'Perfil de Usuario', $usuarioCredencial->id_usuario, "El usuario actualizó su información personal.");

            return back()->with('success', '¡Información de perfil actualizada con éxito!');
        }

        return back()->with('error', 'No se pudo encontrar el perfil.');
    }

    // 1. Mostrar la vista de cambio de contraseña
    public function vistaCambiarPassword(Request $request)
    {
        // Si el usuario entra por primera vez (no viene de enviar un código ni está verificado), limpiamos todo para iniciar en el Paso 1
        if (!$request->session()->has('codigo_enviado') && !$request->session()->has('pwd_verificado')) {
            session()->forget(['codigo_enviado', 'pwd_verificado', 'tipo_cambio_pwd']);
        }

        return view('menu.perfil.cambiar-password-spa');
    }

    // 2. Enviar o Reenviar el código de verificación
    public function enviarCodigoPassword(Request $request)
    {
        $request->validate([
            'tipo_cambio' => 'required|in:manual,aleatorio'
        ]);

        $usuario = Auth::user();
        $correo = $usuario->correo_electronico;

        // Guardamos la preferencia y marcamos que el código fue enviado
        session([
            'tipo_cambio_pwd' => $request->tipo_cambio,
            'codigo_enviado' => true,
            'pwd_verificado' => false
        ]);

        $codigo = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put('codigo_pwd_' . $usuario->id_usuario, $codigo, 60);

        try {
            Mail::to($correo)->send(new CodigoRegistroMail($codigo));
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo enviar el correo electrónico.');
        }

        return back()->with('success', '¡Código de verificación enviado a tu correo electrónico!');
    }

    // 3. Verificar el código ingresado mediante FormRequest
    public function verificarCodigoPassword(VerificarCodigoPasswordRequest $request)
    {
        $usuario = Auth::user();
        $codigoGuardado = Cache::get('codigo_pwd_' . $usuario->id_usuario);

        if (!$codigoGuardado) {
            return back()->with('error', 'El código ha expirado. Por favor, solicita uno nuevo.');
        }

        if ($codigoGuardado !== $request->codigo) {
            return back()->with('error', 'El código ingresado es incorrecto.');
        }

        Cache::forget('codigo_pwd_' . $usuario->id_usuario);

        // Avanzamos al paso 3 de forma inequívoca
        session([
            'codigo_enviado' => false,
            'pwd_verificado' => true
        ]);

        return back()->with('success', '¡Identidad verificada con éxito!');
    }

    // 4. Cambio final de contraseña
    public function actualizarPasswordFinal(Request $request)
    {
        if (!session('pwd_verificado')) {
            return redirect()->route('perfil.seguridad.index')->with('error', 'Acción no autorizada.');
        }

        $usuario = Auth::user();
        $tipoCambio = session('tipo_cambio_pwd', 'aleatorio');

        if ($tipoCambio === 'manual') {
            $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ], [
                'password.required' => 'La nueva contraseña es obligatoria.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            ]);

            $nuevaContrasena = $request->password;
        } else {
            $nuevaContrasena = Str::random(8);

            try {
                Mail::to($usuario->correo_electronico)->send(new NuevaContrasenaMail($nuevaContrasena));
            } catch (\Exception $e) {}
        }

        $usuario->update([
            'contrasenha' => Hash::make($nuevaContrasena)
        ]);

        session()->forget(['pwd_verificado', 'codigo_enviado', 'tipo_cambio_pwd']);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $mensajeExito = $tipoCambio === 'manual'
            ? '¡Contraseña actualizada con éxito! Por favor, inicia sesión con tu nueva clave.'
            : '¡Contraseña actualizada con éxito! Te hemos enviado tu nueva clave por correo.';

        return redirect()->route('login')->with('success', $mensajeExito);
    }
}
