<?php

namespace App\Http\Controllers;

use App\Models\Establecimiento;
use App\Models\Departamento;
use App\Models\Distrito;
use App\Models\Municipio;
use App\Http\Requests\InsertarEstablecimientoRequest;
use App\Http\Requests\ActualizarEstablecimientoRequest;
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
        $distritos = Distrito::orderBy('nombre_distrito', 'ASC')->get();

        // 3. Retornar la vista compactando las 3 variables[cite: 4]
        return view('Establecimiento.establecimiento', compact('establecimientos', 'departamentos', 'municipios', 'distritos'));
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



    // Método para actualizar los datos
    public function update(ActualizarEstablecimientoRequest $request, $id)
    {
        $datos = $request->validated();

        // Verificamos si se adjuntó una nueva imagen
        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('establecimientos', 'public');
        }

        // Llamamos al método estático del modelo para actualizar
        Establecimiento::actualizar($datos, $id);

        return back()->with('success', 'Establecimiento actualizado exitosamente.');
    }

}
