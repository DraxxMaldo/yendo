<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\EmpleadoSucursal;
use App\Models\Establecimiento;
use App\Models\UsuarioCredencial;
use App\Models\PerfilPersona;
use App\Models\Bitacora;
use App\Http\Requests\AlmacenarPersonalRequest;
use App\Mail\NuevaContrasenaMail; // <--- Importamos el Mailable existente

class EmpleadoSucursalController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        // Filtrar establecimientos creados únicamente por el usuario autenticado actual
        $establecimientos = Establecimiento::where('id_usuario', $usuario->id_usuario)->get();

        // Obtener los IDs de esos establecimientos para listar solo su personal
        $establecimientosIds = $establecimientos->pluck('id_establecimiento');

        $empleados = EmpleadoSucursal::whereIn('id_establecimiento', $establecimientosIds)
            ->with(['credenciales.perfil', 'establecimiento'])
            ->get();

        return view('menu.afiliados.gestionarPersonal', compact('empleados', 'establecimientos'));
    }

    public function store(AlmacenarPersonalRequest $request)
    {
        try {
            $datos = $request->validated();
            $usuarioActual = Auth::user();

            // SEGURIDAD: Validar que el establecimiento seleccionado pertenezca al usuario autenticado
            $establecimientoValido = Establecimiento::where('id_establecimiento', $datos['id_establecimiento'])
                ->where('id_usuario', $usuarioActual->id_usuario)
                ->exists();

            if (!$establecimientoValido) {
                return back()->with('error', 'El establecimiento seleccionado no te pertenece o no es válido.');
            }

            // 1. Crear usuario credencial con contraseña temporal aleatoria
            $passwordTemporal = Str::random(8);
            $usuario = UsuarioCredencial::create([
                'correo_electronico' => $datos['correo_electronico'],
                'contrasenha' => Hash::make($passwordTemporal),
                'id_rol' => $datos['id_rol'],
                'estado_cuenta' => true,
            ]);

            // 2. Crear perfil temporal
            PerfilPersona::create([
                'id_usuario' => $usuario->id_usuario,
                'nombres' => 'Por configurar',
                'apellidos' => 'Por configurar',
                'telefono' => '00000000',
            ]);

            // 3. Vincular al establecimiento
            EmpleadoSucursal::create([
                'id_usuario' => $usuario->id_usuario,
                'id_establecimiento' => $datos['id_establecimiento'],
            ]);

            // 4. Enviar correo reutilizando NuevaContrasenaMail con la contraseña temporal
            try {
                Mail::to($usuario->correo_electronico)->send(
                    new NuevaContrasenaMail($passwordTemporal)
                );
            } catch (\Exception $e) {
                // Manejo de error de correo si falla SMTP
            }

            Bitacora::registrar(Bitacora::CREADO, 'Personal de Sucursal', $usuarioActual->id_usuario, "Se registró un nuevo empleado con rol ID {$datos['id_rol']} y se enviaron sus credenciales.");

            return back()->with('success', '¡Personal registrado con éxito! Se han enviado las credenciales de acceso por correo electrónico.');

        } catch (\Throwable $e) {
            return back()->with('error', 'Error al registrar el personal: ' . $e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'id_establecimiento' => ['required', 'integer', 'exists:establecimientos,id_establecimiento'],
        ], [
            'id_establecimiento.required' => 'Debe seleccionar un establecimiento.',
            'id_establecimiento.exists' => 'El establecimiento seleccionado no es válido.',
        ]);

        try {
            $usuarioActual = Auth::user();

            // Seguridad: Validar que el nuevo establecimiento pertenezca al usuario autenticado
            $establecimientoValido = Establecimiento::where('id_establecimiento', $request->id_establecimiento)
                ->where('id_usuario', $usuarioActual->id_usuario)
                ->exists();

            if (!$establecimientoValido) {
                return back()->with('error', 'El establecimiento seleccionado no te pertenece o no es válido.');
            }

            // Buscar la relación en empleado_sucursal
            $empleadoSucursal = EmpleadoSucursal::where('id_usuario', $id)->firstOrFail();

            // Actualizar únicamente el establecimiento asignado
            $empleadoSucursal->update([
                'id_establecimiento' => $request->id_establecimiento
            ]);

            Bitacora::registrar(Bitacora::ACTUALIZADO, 'Personal de Sucursal', $usuarioActual->id_usuario, "Se actualizó el establecimiento asignado para el usuario ID {$id}.");

            return back()->with('success', '¡Establecimiento asignado actualizado correctamente!');

        } catch (\Throwable $e) {
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }
}
