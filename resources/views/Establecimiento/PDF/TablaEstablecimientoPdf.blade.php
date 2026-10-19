<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Establecimientos</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { color: #282b4a; margin: 0; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #282b4a; color: #fff; font-weight: bold; }
        .table tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>
<div class="header">
    <h2>Reporte General de Establecimientos</h2>
    <p>Fecha de generación: {{ now()->format('d/m/Y H:i') }}</p>
</div>

<table class="table">
    <thead>
    <tr>
        <th style="width: 5%;">#</th>
        <th style="width: 25%;">Establecimiento</th>
        <th style="width: 20%;">Departamento</th>
        <th style="width: 20%;">Municipio</th>
        <th style="width: 30%;">Dirección Exacta</th>
    </tr>
    </thead>
    <tbody>
    @forelse($establecimientos as $est)
        <tr>
            <td>{{ $est->id_establecimiento }}</td>
            <td>{{ $est->nombre }}</td>
            <td>{{ $est->municipio->departamento->nombre_departamento ?? 'N/A' }}</td>
            <td>{{ $est->municipio->nombre_municipio ?? 'N/A' }}</td>
            <td>{{ $est->direccion_exacta }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5" style="text-align: center;">No hay establecimientos registrados.</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>
