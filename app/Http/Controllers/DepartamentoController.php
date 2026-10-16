<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    // Método exclusivo para guardar un departamento
    public function store(Request $request)
    {
        // Validamos que el nombre venga en la petición[cite: 12]
        $request->validate([
            'nombre_departamento' => ['required', 'string', 'max:100']
        ]);

        Departamento::create([
            'nombre_departamento' => $request->nombre_departamento
        ]);

        return back()->with('success', 'Departamento registrado exitosamente.');
    }
}
