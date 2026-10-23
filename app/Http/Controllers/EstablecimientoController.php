<?php

namespace App\Http\Controllers;

use App\Models\Establecimiento;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Http\Requests\InsertarEstablecimientoRequest;
use App\Exports\EstablecimientosExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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


    public function store(InsertarEstablecimientoRequest $request)
    {
        $datos = $request->validated();

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('establecimientos', 'public');
        }

        Establecimiento::insertar($datos);

        return back()->with('success', 'Establecimiento registrado exitosamente.');
    }

    // Método para exportar a Excel
    public function exportarExcel()
    {
        // Descarga el archivo generado por la clase EstablecimientosExport
        return Excel::download(new EstablecimientosExport, 'Reporte_Establecimientos.xlsx');
    }

    // Método para exportar a PDF
    public function exportarPdf()
    {
        // Consultamos los datos con sus relaciones (Eager Loading)
        $establecimientos = Establecimiento::with('municipio.departamento')->orderBy('id_establecimiento', 'DESC')->get();

        // Cargamos la vista que acabamos de crear y le pasamos los datos
        $pdf = Pdf::loadView('Establecimiento.PDF.TablaEstablecimientoPdf', compact('establecimientos'));

        // Descargamos el archivo generado
        return $pdf->download('Reporte_Establecimientos.pdf');
    }
}
