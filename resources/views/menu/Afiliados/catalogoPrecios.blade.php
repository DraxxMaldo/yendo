@extends('layouts.layout')

@section('titulo', 'Catálogo de Precios | Yendo')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
    <link rel="stylesheet" href="{{ asset('css/catalogo-precios.css') }}">
@endpush

@section('contenido')
    @include('layouts.toast')

    <div class="container-fluid py-4">
        <div class="row">

            {{-- COLUMNA IZQUIERDA: Formulario para Agregar Nuevo Precio --}}
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-lg p-4" style="background-color: var(--vanilla-cream); border: 2px solid var(--midnight-indigo) !important; border-radius: 10px;">
                    <h4 class="fw-bold mb-3" style="color: var(--midnight-indigo);">
                        <i class="fas fa-tags mr-2" style="color: var(--accent-primary);"></i> Nuevo Precio
                    </h4>
                    <p class="text-muted small mb-4">Define los costos estándar de tus prendas, cajas o servicios para tus sucursales.</p>

                    <form action="{{ route('catalogo.precios.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="fw-bold small text-uppercase">Nombre de la Prenda / Servicio</label>
                            <input type="text" name="nombre_prenda" class="form-control @error('nombre_prenda') is-invalid @enderror" value="{{ old('nombre_prenda') }}" placeholder="Ej. Caja Mediana, Pantalón, etc." required>
                            @error('nombre_prenda') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="fw-bold small text-uppercase">Precio Estándar ($)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white">$</span>
                                </div>
                                <input type="number" step="0.01" min="0" name="precio" class="form-control @error('precio') is-invalid @enderror" value="{{ old('precio', '0.00') }}" required>
                            </div>
                            @error('precio') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <button type="submit" class="btn w-100 fw-bold rounded-pill py-2 shadow-sm" style="background-color: var(--midnight-indigo); color: var(--vanilla-cream);">
                            <i class="fas fa-plus-circle mr-1"></i> Guardar en el Catálogo
                        </button>
                    </form>
                </div>
            </div>

            {{-- COLUMNA DERECHA: Tabla con los Precios Registrados --}}
            <div class="col-md-8">
                <div class="card border-0 shadow-lg p-4" style="background-color: var(--vanilla-cream); border: 2px solid var(--midnight-indigo) !important; border-radius: 10px;">
                    <h4 class="fw-bold mb-3" style="color: var(--midnight-indigo);">
                        <i class="fas fa-list-alt mr-2" style="color: var(--accent-primary);"></i> Tu Catálogo Actual
                    </h4>
                    <p class="text-muted small mb-4">Listado de tarifas disponibles para las operaciones en tus establecimientos.</p>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead style="background-color: var(--midnight-indigo); color: var(--vanilla-cream);">
                            <tr>
                                <th class="py-3">#</th>
                                <th class="py-3">Prenda / Servicio</th>
                                <th class="py-3">Precio Estándar</th>
                                <th class="py-3 text-center">Estado</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($precios as $index => $item)
                                <tr>
                                    <td class="fw-bold">{{ $index + 1 }}</td>
                                    <td>{{ $item->nombre_prenda }}</td>
                                    <td class="fw-bold text-success">${{ number_format($item->precio, 2) }}</td>
                                    <td class="text-center">
                                        @if($item->estado)
                                            <span class="badge badge-success px-3 py-2">Activo</span>
                                        @else
                                            <span class="badge badge-secondary px-3 py-2">Inactivo</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle mr-1"></i> Aún no has registrado ningún precio en tu catálogo.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
