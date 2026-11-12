@extends('layouts.layout')

@section('titulo', 'Gestión de Empleados de Sucursal | Yendo')

@push('estilos')
    {{-- CSS de DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
@endpush

@section('contenido')
    @include('layouts.toast')

    <div class="container-fluid py-4">
        <div class="card border-0 shadow-lg p-4" style="background-color: var(--vanilla-cream); border: 2px solid var(--midnight-indigo) !important;">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="fw-bold" style="color: var(--midnight-indigo);">
                        <i class="fas fa-users mr-2"></i> Gestión de Empleados y Sucursales
                    </h2>
                    <p class="text-muted mb-0">Control del personal asignado a los establecimientos de la red.</p>
                </div>
            </div>

            <div class="table-responsive bg-white p-3 rounded border border-dark mt-3">
                <table id="tbl" class="table table-hover align-middle mb-0 w-100">
                    <thead class="thead-dark" style="background-color: var(--midnight-indigo); color: var(--vanilla-cream);">
                    <tr>
                        <th>ID Usuario</th>
                        <th>Nombre del Empleado</th>
                        <th>Correo Electrónico</th>
                        <th>Establecimiento Asignado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($empleados as $emp)
                        <tr>
                            <td>#US-{{ str_pad($emp->id_usuario, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="fw-bold">{{ $emp->credenciales->perfil->nombres ?? 'N/A' }} {{ $emp->credenciales->perfil->apellidos ?? '' }}</td>
                            <td>{{ $emp->credenciales->correo_electronico ?? 'N/A' }}</td>
                            <td><i class="fas fa-building mr-1 text-muted"></i> {{ $emp->establecimiento->nombre ?? 'Sin establecimiento' }}</td>
                            <td class="text-center">
                                <span class="badge badge-primary px-3 py-2" style="background-color: var(--midnight-indigo);">Asignado</span>
                            </td>
                        </tr>
                    @empty
                        {{-- Dejar vacío para DataTables --}}
                    @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    {{-- JS de DataTables --}}
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('js/dataTable.js') }}"></script>
@endpush
