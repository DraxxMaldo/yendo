<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Establecimiento;

class DestinoController extends Controller
{
    public function index()
    {
        $destinos = Establecimiento::all();

        // Apuntamos a la carpeta Nav y luego al archivo destinos
        return view('Nav.destinos', compact('destinos'));
    }
}
