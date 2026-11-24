<?php

namespace App\Http\Controllers;

use App\Models\Establecimiento;
use App\Models\Departamento;
use App\Models\Distrito;
use App\Models\Municipio;
use App\Http\Requests\InsertarEstablecimientoRequest;
use App\Http\Requests\ActualizarEstablecimientoRequest;
use App\Exports\EstablecimientosExport;
use App\Models\Zona;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class EstablecimientoController extends Controller
{
    public function index()
    {
        $establecimientos = $this->establecimientosVisibles()
            ->with('distrito.municipio.departamento', 'distrito.municipio.departamento.zona','creador.perfil')
            ->orderBy('id_establecimiento', 'DESC')
            ->get();

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

        $datos['id_usuario'] = Auth::user()->id_usuario;

        Establecimiento::insertar($datos);

        return back()->with('success', 'Establecimiento registrado exitosamente.');
    }

    // Método para exportar a Excel
    public function exportarExcel()
    {
        $usuario = Auth::user();

        // El administrador conserva el reporte global; el afiliado exporta solo sus establecimientos.
        $idUsuario = $usuario->tieneAlgunRol(['admin']) ? null : $usuario->id_usuario;

        return Excel::download(new EstablecimientosExport($idUsuario), 'Reporte_Establecimientos.xlsx');
    }

    // Método para exportar a PDF
    public function exportarPdf()
    {
        // Consultamos los datos con las mismas reglas de acceso que el listado.
        $establecimientos = $this->establecimientosVisibles()
            ->with('distrito.municipio.departamento')
            ->orderBy('id_establecimiento', 'DESC')
            ->get();

        // Cargamos la vista que acabamos de crear y le pasamos los datos
        $pdf = Pdf::loadView('Establecimiento.PDF.TablaEstablecimientoPdf', compact('establecimientos'));

        // Descargamos el archivo generado
        return $pdf->download('Reporte_Establecimientos.pdf');
    }



    // Método para actualizar los datos
    public function update(ActualizarEstablecimientoRequest $request, $id)
    {
        // Primero verificamos que el establecimiento pertenezca al usuario o sea visible para el administrador.
        // Se realiza antes de almacenar una posible imagen para evitar escrituras no autorizadas.
        $establecimiento = $this->establecimientosVisibles()->findOrFail($id);

        $datos = $request->validated();

        // Verificamos si se adjuntó una nueva imagen
        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('establecimientos', 'public');
        }

        // Llamamos al método estático del modelo para actualizar
        Establecimiento::actualizar($datos, $establecimiento->id_establecimiento);

        return back()->with('success', 'Establecimiento actualizado exitosamente.');
    }

    /**
     * Consulta los establecimientos que puede visualizar el usuario autenticado.
     * El administrador mantiene acceso global y cada afiliado queda limitado a sus registros.
     */
    private function establecimientosVisibles()
    {
        $usuario = Auth::user();
        $consulta = Establecimiento::query();

        if (!$usuario->tieneAlgunRol(['admin'])) {
            $consulta->where('id_usuario', $usuario->id_usuario);
        }

        return $consulta;
    }

}
