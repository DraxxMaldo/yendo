<?php

namespace App\Http\Controllers;

use App\Models\Establecimiento;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Http\Requests\InsertarEstablecimientoRequest; // 1. Importamos tu nuevo Form Request

class EstablecimientoController extends Controller
{
    public function index()
    {
        // 1. Consultar establecimientos con su relación
        $establecimientos = Establecimiento::with('municipio.departamento')->orderBy('id_establecimiento', 'DESC')->get();

        // 2. Consultar catálogos para los modales
        $departamentos = Departamento::orderBy('nombre_departamento', 'ASC')->get();
        $municipios = Municipio::orderBy('nombre_municipio', 'ASC')->get();

        // 3. Retornar la vista compactando las 3 variables[cite: 4]
        return view('Establecimiento.establecimiento', compact('establecimientos', 'departamentos', 'municipios'));
    }

    // 2. Inyectamos el Request personalizado en lugar del Request genérico[cite: 4]
    public function store(InsertarEstablecimientoRequest $request)
    {
        // 3. Como el Request ya validó los datos, usamos validated() para obtener el arreglo limpio
        Establecimiento::insertar($request->validated());

        return back()->with('success', 'Establecimiento registrado exitosamente.');
    }
}
