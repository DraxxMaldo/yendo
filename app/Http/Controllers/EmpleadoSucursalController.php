<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmpleadoSucursal;
use App\Models\Establecimiento;

class EmpleadoSucursalController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        // Si es administrador (rol 1), ve todos los empleados; si es afiliado (rol 2), solo los de sus establecimientos
        if ($usuario->id_rol == 1) {
            $empleados = EmpleadoSucursal::mostrarTodos();
        } else {
            $establecimientosIds = Establecimiento::where('id_usuario', $usuario->id_usuario)->pluck('id_establecimiento');
            $empleados = EmpleadoSucursal::whereIn('id_establecimiento', $establecimientosIds)->with(['credenciales.perfil', 'establecimiento'])->get();
        }

        // Apuntamos a la nueva ruta de la vista dentro de menu/afiliados/
        return view('menu.afiliados.gestionarPersonal', compact('empleados'));
    }
}
