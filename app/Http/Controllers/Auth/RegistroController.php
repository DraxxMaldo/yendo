<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ValidarCorreoRegistroRequest;
use App\Mail\CodigoRegistroMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Models\PerfilPersona;
use App\Http\Requests\Auth\CompletarRegistroRequest;
use App\Models\UsuarioCredencial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class RegistroController extends Controller
{
    // Método para generar y enviar el código
    public function enviarCodigo(ValidarCorreoRegistroRequest $request)
    {
        // Si llega a esta línea, es porque pasó todas las validaciones de tu Request con éxito
        $correo = $request->correo;

        // Genera un código aleatorio de 6 dígitos
        $codigo = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

        // Guarda el código en Caché por 1 minuto (60 segundos)
        Cache::put('codigo_registro_' . $correo, $codigo, 60);

        // Envía el correo
        Mail::to($correo)->send(new CodigoRegistroMail($codigo));

        return response()->json(['success' => true, 'mensaje' => 'Código enviado al correo.']);
    }

    // Método para verificar si el código ingresado coincide
    public function verificarCodigo(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'codigo' => 'required|digits:6'
        ]);

        $codigoGuardado = Cache::get('codigo_registro_' . $request->correo);

        if (!$codigoGuardado) {
            return response()->json(['success' => false, 'mensaje' => 'El código ha expirado. Por favor, solicita uno nuevo.']);
        }

        if ($codigoGuardado !== $request->codigo) {
            return response()->json(['success' => false, 'mensaje' => 'El código es incorrecto.']);
        }

        // Si es correcto, podemos borrarlo de caché por seguridad
        Cache::forget('codigo_registro_' . $request->correo);

        return response()->json(['success' => true]);
    }

    // NUEVO MÉTODO: Guarda los datos finales en las dos tablas
    public function finalizarRegistro(CompletarRegistroRequest $request)
    {
        try {
            DB::beginTransaction();

            // 1. Crear el usuario en usuarios_credenciales
            $usuario = UsuarioCredencial::create([
                'correo_electronico' => $request->correo_final,
                'contrasenha'        => Hash::make($request->password), // Encriptamos la contraseña
                'id_rol'             => 3, // 3 = Básico
                'estado_cuenta'      => 1  // 1 = Activo
            ]);

            // 2. Crear el perfil asociado en perfiles_personas
            PerfilPersona::create([
                'id_usuario'     => $usuario->id_usuario,
                'nombres'        => $request->nombres,
                'apellidos'      => $request->apellidos,
                // Agregamos valores por defecto para que la BD no explote por campos vacíos
                'telefono'       => '00000000',
                'nombre_negocio' => 'Por definir'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'mensaje' => '¡Cuenta creada con éxito! Ahora puedes iniciar sesión.'
            ]);

            // Cambiamos Exception por Throwable para atrapar TODO
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => 'Error BD: ' . $e->getMessage()
            ], 500);
        }
    }
}
