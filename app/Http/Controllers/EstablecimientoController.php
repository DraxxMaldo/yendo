<?php

namespace App\Http\Controllers;

use App\Models\Establecimiento;
use App\Models\Departamento;
use App\Models\Municipio;
use Illuminate\Http\Request;

class EstablecimientoController extends Controller
{
    public function index()
    {
        // 1. Consultar establecimientos con su relación[cite: 14]
        $establecimientos = Establecimiento::with('municipio.departamento')->orderBy('id_establecimiento', 'DESC')->get();

        // 2. Consultar catálogos para los modales
        $departamentos = Departamento::orderBy('nombre_departamento', 'ASC')->get();
        $municipios = Municipio::orderBy('nombre_municipio', 'ASC')->get();

        // 3. Retornar la vista compactando las 3 variables[cite: 14]
        return view('Establecimiento.establecimiento', compact('establecimientos', 'departamentos', 'municipios'));
    }

    public function store(Request $request)
    {
        // Usamos la lógica de validación e inserción que ya tienes en tu modelo
        $datosValidados = Establecimiento::rules($request->all());
        Establecimiento::insertar($datosValidados);

        return back()->with('success', 'Establecimiento registrado exitosamente.');
    }
}
