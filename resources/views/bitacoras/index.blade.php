@extends('layouts.layout')

@section('titulo', 'Bitácora de Auditoría')

@section('contenido')
    <div class="card mt-4 shadow-sm border-0">
        <div class="card-header bg-white pt-4 pb-2 px-4 border-0">
            <h3 class="card-title fw-bold" style="color: var(--midnight-indigo); font-size: 1.5rem;">
                <i class="fas fa-shield-alt mr-2"></i> Trazabilidad del Sistema
            </h3>
        </div>

        <div class="card-body px-4 pb-4">
            {{-- FORMULARIO DE FILTROS (MÉTODO GET) --}}
            <form method="GET" action="{{ route('bitacora') }}" class="row mb-4 bg-light p-3 rounded">
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold text-uppercase">Evento</label>
                    <select name="evento" class="form-control">
                        <option value="">Todos los eventos</option>
                        @foreach($eventos as $evt)
                            <option value="{{ $evt }}" {{ request('evento') == $evt ? 'selected' : '' }}>
                                {{ $evt }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold text-uppercase">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold text-uppercase">Fecha Fin</label>
                    <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary fw-bold w-100 mr-2">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <a href="{{ route('bitacora') }}" class="btn btn-outline-secondary fw-bold w-100">
                        Limpiar
                    </a>
                </div>
            </form>

            {{-- TABLA DE REGISTROS --}}
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Evento</th>
                        <th>Usuario (IP)</th>
                        <th>Módulo</th>
                        <th>Detalle</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{-- @forelse es la forma profesional de renderizar listados manejando estados vacíos[cite: 4] --}}
                    @forelse($bitacoras as $b)
                        <tr>
                            <td class="text-muted small fw-bold">
                                {{ $b->created_at->format('d/m/Y h:i A') }}
                            </td>
                            <td>
                                {{-- Insignias de color diferenciadas usando @switch[cite: 4] --}}
                                @switch($b->evento)
                                    @case('LOGIN_OK')
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle"></i> LOGIN</span>
                                        @break
                                    @case('LOGIN_FALLIDO')
                                        <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle"></i> ERROR LOGIN</span>
                                        @break
                                    @case('LOGOUT')
                                        <span class="badge badge-secondary px-2 py-1"><i class="fas fa-sign-out-alt"></i> LOGOUT</span>
                                        @break
                                    @case('CREADO')
                                        <span class="badge badge-primary px-2 py-1"><i class="fas fa-plus"></i> CREADO</span>
                                        @break
                                    @case('ACTUALIZADO')
                                        <span class="badge badge-info px-2 py-1"><i class="fas fa-edit"></i> EDITADO</span>
                                        @break
                                    @case('ELIMINADO')
                                        <span class="badge badge-warning px-2 py-1"><i class="fas fa-trash"></i> ELIMINADO</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                <span class="d-block fw-bold text-dark">{{ $b->usuario_nombre ?? 'Desconocido' }}</span>
                                <small class="text-muted"><i class="fas fa-network-wired"></i> {{ $b->ip_address }}</small>
                            </td>
                            <td class="text-muted">
                                {{ $b->modulo ?? '—' }} <br>
                                @if($b->registro_id) <small>ID: {{ $b->registro_id }}</small> @endif
                            </td>
                            <td class="small">{{ $b->descripcion }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="fas fa-folder-open mb-3" style="font-size: 3rem; opacity: 0.5;"></i><br>
                                No se encontraron registros de auditoría con los filtros aplicados.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
