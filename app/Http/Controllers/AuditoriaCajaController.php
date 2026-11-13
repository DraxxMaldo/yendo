<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaccionPaquete;

class AuditoriaCajaController extends Controller
{
    public function index()
    {
        $transacciones = TransaccionPaquete::with(['encomienda.remitente', 'encomienda.origen', 'encomienda.destino', 'cajero', 'concepto'])
            ->orderBy('fecha_transaccion', 'DESC')
            ->paginate(10);

        // Ruta corregida apuntando a menu/cajero/auditoria/
        return view('menu.cajero.auditoria.paquetesRegistrados', compact('transacciones'));
    }
}
