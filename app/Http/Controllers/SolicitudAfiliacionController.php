<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\SolicitudAfiliacion;
use App\Models\Bitacora;
use App\Mail\RespuestaAfiliacionMail;

class SolicitudAfiliacionController extends Controller
{
    public function store(Request $request)
    {
        $usuario = Auth::user();

        if ($usuario->id_rol != 3) {
            return back()->with('error', 'Solo los usuarios con rol básico pueden solicitar afiliación.');
        }

        $existente = SolicitudAfiliacion::where('id_usuario', $usuario->id_usuario)
            ->where('estado', 'pendiente')
            ->first();

        if ($existente) {
            return back()->with('warning', 'Ya cuentas con una solicitud de afiliación en proceso.');
        }

        SolicitudAfiliacion::create([
            'id_usuario' => $usuario->id_usuario,
            'estado' => 'pendiente'
        ]);

        Bitacora::registrar(Bitacora::CREADO, 'Solicitud de Afiliación', $usuario->id_usuario, "El usuario solicitó convertirse en afiliado.");

        return back()->with('success', '¡Solicitud de afiliación enviada con éxito! Un administrador la revisará pronto.');
    }

    public function indexAdmin()
    {
        $solicitudes = SolicitudAfiliacion::with(['usuario.perfil'])->orderBy('created_at', 'desc')->get();

        return view('admin.solicitudes-afiliacion', compact('solicitudes'));
    }

    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'accion' => 'required|in:aprobar,rechazar'
        ]);

        $solicitud = SolicitudAfiliacion::findOrFail($id);
        $usuarioAfiliado = $solicitud->usuario;
        $correoUsuario = $usuarioAfiliado->correo_electronico;

        if ($request->accion === 'aprobar') {
            $solicitud->update(['estado' => 'aprobada']);

            // Cambiar rol del usuario a Afiliado (id_rol = 2)
            $usuarioAfiliado->update(['id_rol' => 2]);

            Bitacora::registrar(Bitacora::ACTUALIZADO, 'Solicitud de Afiliación', Auth::id(), "El administrador aprobó la afiliación del usuario #{$usuarioAfiliado->id_usuario}.");

            // Enviar correo de aprobación
            try {
                Mail::to($correoUsuario)->send(new RespuestaAfiliacionMail('aprobada'));
            } catch (\Exception $e) {
                // Opcional: manejar error de correo si el servidor SMTP falla
            }

            return back()->with('success', '¡Solicitud aprobada y correo enviado al usuario con éxito!');
        } else {
            $motivo = $request->motivo_rechazo ?? 'No especificado';

            $solicitud->update([
                'estado' => 'rechazada',
                'motivo_rechazo' => $motivo
            ]);

            // Enviar correo de rechazo
            try {
                Mail::to($correoUsuario)->send(new RespuestaAfiliacionMail('rechazada', $motivo));
            } catch (\Exception $e) {}

            return back()->with('success', 'La solicitud ha sido rechazada y se notificó al usuario por correo.');
        }
    }
}
