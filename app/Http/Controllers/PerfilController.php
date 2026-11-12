<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PerfilPersona;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ActualizarPerfilPropioRequest;
use App\Models\Bitacora;

class PerfilController extends Controller
{
    public function index()
    {
        $usuarioCredencial = Auth::user();
        $perfilPersona = PerfilPersona::where('id_usuario', $usuarioCredencial->id_usuario)->first();

        $nombreRol = 'Usuario';
        if ($usuarioCredencial->id_rol == 1) {
            $nombreRol = 'Administrador';
        } elseif ($usuarioCredencial->id_rol == 2) {
            $nombreRol = 'Afiliado';
        } elseif ($usuarioCredencial->id_rol == 3) {
            $nombreRol = 'Básico';
        }

        $urlImagen = $perfilPersona && $perfilPersona->imagen
            ? asset('storage/' . $perfilPersona->imagen)
            : 'https://ui-avatars.com/api/?name=' . urlencode(($perfilPersona ? $perfilPersona->nombres : 'Usuario') . ' ' . ($perfilPersona ? $perfilPersona->apellidos : '')) . '&background=EEEBDA&color=282B4A&size=180&bold=true';

        $datosUsuario = [
            'id_usuario'     => $usuarioCredencial->id_usuario,
            'correo'         => $usuarioCredencial->correo_electronico,
            'nombres'        => $perfilPersona ? $perfilPersona->nombres : '',
            'apellidos'      => $perfilPersona ? $perfilPersona->apellidos : '',
            'telefono'       => $perfilPersona ? $perfilPersona->telefono : '',
            'nombre_negocio' => $perfilPersona ? $perfilPersona->nombre_negocio : '',
            'imagen'         => $urlImagen,
            'rol'            => $nombreRol,
        ];

        return view('menu.perfil.perfil', compact('datosUsuario'));
    }

    public function actualizarFoto(Request $request)
    {
        // Validamos que sea una imagen y que no pese más de 2MB
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
                // Si ya tenía una imagen previa, la eliminamos del storage
                if ($perfilPersona->imagen && Storage::disk('public')->exists($perfilPersona->imagen)) {
                    Storage::disk('public')->delete($perfilPersona->imagen);
                }

                // Guardamos la nueva imagen
                $rutaImagen = $request->file('imagen')->store('perfiles', 'public');

                // Actualizamos la base de datos
                $perfilPersona->update([
                    'imagen' => $rutaImagen
                ]);

                // Bitácora de auditoría
                Bitacora::registrar(Bitacora::ACTUALIZADO, 'Perfil de Usuario', $usuarioCredencial->id_usuario, "El usuario actualizó su fotografía de perfil.");

                // FLUJO ESTÁNDAR: Retornamos atrás con la sesión de éxito
                return back()->with('success', '¡Fotografía de perfil actualizada correctamente!');
            }

            return back()->with('error', 'No se ha seleccionado ninguna imagen.');

        } catch (\Throwable $e) {
            return back()->with('error', 'Error al actualizar la foto: ' . $e->getMessage());
        }
    }

    public function actualizarInformacion(ActualizarPerfilPropioRequest $request)
    {
        // El FormRequest ya se encargó de validar todo limpiamente
        $datos = $request->validated();

        $usuarioCredencial = Auth::user();
        $perfilPersona = PerfilPersona::where('id_usuario', $usuarioCredencial->id_usuario)->first();

        if ($perfilPersona) {
            $perfilPersona->update([
                'nombres'        => $datos['nombres'],
                'apellidos'      => $datos['apellidos'],
                'telefono'       => $datos['telefono'],
                'nombre_negocio' => $datos['nombre_negocio'],
            ]);

            Bitacora::registrar(Bitacora::ACTUALIZADO, 'Perfil de Usuario', $usuarioCredencial->id_usuario, "El usuario actualizó su información personal.");

            // Redirige de vuelta y activará automáticamente el toast global gracias a with('success')
            return back()->with('success', '¡Información de perfil actualizada con éxito!');
        }

        return back()->with('error', 'No se pudo encontrar el perfil.');
    }
}
