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
    /**
     * 1. Obtenemos los datos con sus relaciones para evitar el problema N+1
     */
    public function collection()
    {
        return Establecimiento::with('municipio.departamento')->orderBy('id_establecimiento', 'DESC')->get();
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
            $establecimiento->municipio->departamento->nombre_departamento ?? 'Sin Departamento',
            $establecimiento->municipio->nombre_municipio ?? 'Sin Municipio',
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
