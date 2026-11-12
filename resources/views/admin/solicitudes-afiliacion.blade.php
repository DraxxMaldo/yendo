@extends('layouts.layout')

@section('titulo', 'Gestión de Solicitudes de Afiliación | Yendo')

@push('estilos')
    {{-- CSS de DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
    <link rel="stylesheet" href="{{ asset('css/solicitudes-afiliacion.css') }}">
@endpush

@section('contenido')
    @include('layouts.toast')

    <div class="container-fluid py-4">
        <div class="card border-0 shadow-lg p-4 card-solicitudes-custom">

            <h2 class="fw-bold mb-3 titulo-solicitudes">
                <i class="fas fa-users-cog mr-2"></i> Solicitudes de Afiliación
            </h2>
            <p class="text-muted mb-4">Revisa las solicitudes enviadas por los usuarios y aprueba o rechaza de forma manual.</p>

            <div class="table-responsive p-3 rounded table-container-custom">
                <table id="tbl" class="table table-hover align-middle mb-0 w-100">
                    <thead class="thead-dark table-solicitudes-header">
                    <tr>
                        <th>ID Usuario</th>
                        <th>Nombre Completo</th>
                        <th>Correo</th>
                        <th>Negocio</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($solicitudes as $solicitud)
                        <tr>
                            <td>#US-{{ str_pad($solicitud->id_usuario, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="fw-bold">{{ $solicitud->usuario->perfil->nombres ?? 'N/A' }} {{ $solicitud->usuario->perfil->apellidos ?? '' }}</td>
                            <td>{{ $solicitud->usuario->correo_electronico }}</td>
                            <td>{{ $solicitud->usuario->perfil->nombre_negocio ?? 'No especificado' }}</td>
                            <td>
                                @if($solicitud->estado == 'pendiente')
                                    <span class="badge badge-warning px-2 py-1">Pendiente</span>
                                @elseif($solicitud->estado == 'aprobada')
                                    <span class="badge badge-success px-2 py-1">Aprobada</span>
                                @else
                                    <span class="badge badge-danger px-2 py-1">Rechazada</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($solicitud->estado == 'pendiente')
                                    <form action="{{ route('admin.solicitudes.estado', $solicitud->id_solicitud) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="accion" value="aprobar">
                                        <button type="submit" class="btn btn-sm btn-success fw-bold px-3">
                                            <i class="fas fa-check mr-1"></i> Aprobar
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.solicitudes.estado', $solicitud->id_solicitud) }}" method="POST" class="d-inline ml-1">
                                        @csrf
                                        <input type="hidden" name="accion" value="rechazar">
                                        <button type="submit" class="btn btn-sm btn-danger fw-bold px-3">
                                            <i class="fas fa-times mr-1"></i> Rechazar
                                        </button>
                                    </form>
                                @else
                                    <span class="small text-muted font-weight-bold">Gestionada</span>
                                @endif
                            </td>
                        </tr>
                    @empty
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

    {{-- Tu componente reutilizable de DataTable --}}
    <script src="{{ asset('js/dataTable.js') }}"></script>
@endpush
