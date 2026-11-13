<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TransaccionPaquete;

class AuditoriaCajaController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $buscar = $request->input('buscar');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $contraentrega = $request->input('contraentrega');

        // Consulta base filtrada por el cajero autenticado
        $query = TransaccionPaquete::with(['encomienda.remitente', 'encomienda.origen', 'encomienda.destino', 'concepto'])
            ->where('id_usuario_cajero', $usuario->id_usuario)
            ->orderBy('fecha_transaccion', 'DESC');

        if (!empty($buscar)) {
            $query->whereHas('encomienda', function($subQuery) use ($buscar) {
                $subQuery->where('codigo_rastreo', 'like', "%{$buscar}%")
                    ->orWhereHas('remitente', function($remQuery) use ($buscar) {
                        $remQuery->where('correo_electronico', 'like', "%{$buscar}%");
                    });
            });
        }

        if (!empty($fechaInicio)) {
            $query->whereDate('fecha_transaccion', '>=', $fechaInicio);
        }
        if (!empty($fechaFin)) {
            $query->whereDate('fecha_transaccion', '<=', $fechaFin);
        }

        if ($contraentrega !== null && $contraentrega !== '') {
            $query->whereHas('encomienda', function($subQuery) use ($contraentrega) {
                $subQuery->where('es_pago_contraentrega', $contraentrega);
            });
        }

        // CLONAR LA CONSULTA ANTES DE PAGINAR PARA CALCULAR LOS TOTALES DINÁMICOS
        $montoTotalAcumulado = (clone $query)->sum('monto');

        $transacciones = $query->paginate(9)->appends($request->all());

        return view('menu.cajero.auditoria.paquetesRegistrados', compact(
            'transacciones',
            'buscar',
            'fechaInicio',
            'fechaFin',
            'contraentrega',
            'montoTotalAcumulado' // <--- Pasamos el total en dinero a la vista
        ));
    }
}
