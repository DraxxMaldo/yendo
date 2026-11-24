<?php

namespace App\Exports;

use App\Models\Establecimiento;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EstablecimientosExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private ?int $idUsuario;

    public function __construct(?int $idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    /**
     * 1. Obtenemos los datos con sus relaciones para evitar el problema N+1.
     * Si recibe un ID de usuario, limita el reporte a sus establecimientos.
     * Si recibe null, conserva el reporte global del administrador.
     */
    public function collection()
    {
        $consulta = Establecimiento::with('distrito.municipio.departamento')
            ->orderBy('id_establecimiento', 'DESC');

        if ($this->idUsuario !== null) {
            $consulta->where('id_usuario', $this->idUsuario);
        }

        return $consulta->get();
    }

    /**
     * 2. Definimos los encabezados de las columnas
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nombre del Establecimiento',
            'Departamento',
            'Municipio',
            'Dirección Exacta'
        ];
    }

    /**
     * 3. Mapeamos qué dato de la base de datos va en qué columna
     */
    public function map($establecimiento): array
    {
        return [
            $establecimiento->id_establecimiento,
            $establecimiento->nombre,
            $establecimiento->distrito?->municipio?->departamento?->nombre_departamento ?? 'Sin Departamento',
            $establecimiento->distrito?->municipio?->nombre_municipio ?? 'Sin Municipio',
            $establecimiento->direccion_exacta,
        ];
    }

    /**
     * 4. Estilos (Le ponemos negrita a la primera fila de encabezados)
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
