<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;

class MunicipioController extends Controller
{
    // Método exclusivo para guardar un municipio
    public function store(Request $request)
    {
        // Validamos la llave foránea y el nombre[cite: 12]
        $request->validate([
            'id_departamento'  => ['required', 'integer'],
            'nombre_municipio' => ['required', 'string', 'max:100']
        ]);

        Municipio::create([
            'id_departamento'  => $request->id_departamento,
            'nombre_municipio' => $request->nombre_municipio
        ]);

        return back()->with('success', 'Municipio registrado exitosamente.');
    }
}
