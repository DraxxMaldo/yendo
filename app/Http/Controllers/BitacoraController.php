<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    public function index(Request $request)
    {
        // 1. Iniciamos la consulta cargando la relación del usuario
        $query = Bitacora::with('usuario')->orderBy('created_at', 'DESC');

        // 2. Filtro por Evento
        if ($request->filled('evento')) {
            $query->where('evento', $request->evento);
        }

        // 3. Filtros por Rango de Fechas
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        // 4. Obtenemos los resultados
        $bitacoras = $query->get();

        // 5. Lista de eventos para llenar el <select> del formulario
        $eventos = [
            Bitacora::LOGIN_OK, Bitacora::LOGIN_FALLIDO, Bitacora::LOGOUT,
            Bitacora::CREADO, Bitacora::ACTUALIZADO, Bitacora::ELIMINADO
        ];

        // 6. Retornamos la vista pasando los datos
        return view('bitacoras.index', compact('bitacoras', 'eventos'));
    }
}
