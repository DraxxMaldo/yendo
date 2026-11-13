@extends('layouts.layout')

@section('titulo', 'Mis Paquetes Registrados | Yendo')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
    <link rel="stylesheet" href="{{ asset('css/paquetes-registrados.css') }}">
@endpush

@section('contenido')
    @include('layouts.toast')

    <div class="container-fluid py-4">

        <!-- Cabecera -->
        <div class="d-flex flex-column mb-4">
            <h2 class="fw-bold" style="color: var(--midnight-indigo);">
                <i class="fas fa-id-card mr-2" style="color: var(--accent-primary);"></i> Historial de Paquetes Ingresados por Mí
            </h2>
            <p class="text-muted mb-0">Trazabilidad de las transacciones y encomiendas registradas bajo tu usuario de caja.</p>
        </div>

        <!-- TARJETA DE FILTROS PERSONALES -->
        <div class="card border-0 shadow-sm mb-4 p-4" style="background-color: var(--vanilla-cream); border: 2px solid var(--midnight-indigo) !important; border-radius: 10px;">
            <form action="{{ route('auditoria.paquetes') }}" method="GET">
                <div class="row align-items-end">

                    <!-- Búsqueda General -->
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold small text-uppercase text-secondary">Buscar Código / Correo</label>
                        <input type="text" name="buscar" class="form-control" placeholder="Ej. YND-ABC123" value="{{ request('buscar') }}">
                    </div>

                    <!-- Filtro por Tipo de Pago -->
                    <div class="col-md-3 mb-3">
                        <label class="fw-bold small text-uppercase text-secondary">Pago Contraentrega</label>
                        <select name="contraentrega" class="form-control">
                            <option value="">-- Todos mis paquetes --</option>
                            <option value="1" {{ request('contraentrega') === '1' ? 'selected' : '' }}>Sí</option>
                            <option value="0" {{ request('contraentrega') === '0' ? 'selected' : '' }}>No (Pagado)</option>
                        </select>
                    </div>

                    <!-- Fecha Inicio -->
                    <div class="col-md-2 mb-3">
                        <label class="fw-bold small text-uppercase text-secondary">Desde</label>
                        <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
                    </div>

                    <!-- Fecha Fin -->
                    <div class="col-md-3 mb-3">
                        <label class="fw-bold small text-uppercase text-secondary">Hasta</label>
                        <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                    </div>

                </div>

                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn px-4 fw-bold mr-2 text-white shadow-sm" style="background-color: var(--midnight-indigo);">
                        <i class="fas fa-filter mr-1"></i> Filtrar Mis Registros
                    </button>
                    @if(request()->anyFilled(['buscar', 'contraentrega', 'fecha_inicio', 'fecha_fin']))
                        <a href="{{ route('auditoria.paquetes') }}" class="btn btn-outline-secondary fw-bold px-3">
                            <i class="fas fa-sync-alt mr-1"></i> Limpiar Filtros
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- TARJETA RESUMEN DINÁMICA (TOTAL DE PAQUETES Y MONTO) -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm p-3 bg-white d-flex flex-row justify-content-between align-items-center" style="border-left: 5px solid var(--midnight-indigo) !important; border-radius: 8px;">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded mr-3 text-white" style="background-color: var(--midnight-indigo);">
                            <i class="fas fa-boxes fa-2x"></i>
                        </div>
                        <div>
                            <span class="text-muted small text-uppercase fw-bold d-block">Paquetes Mostrados / Filtrados</span>
                            <h3 class="fw-bold text-dark mb-0">{{ $transacciones->total() }} <span class="fs-6 text-muted font-weight-normal">paquete(s)</span></h3>
                        </div>
                    </div>
                    <div class="text-right pr-3">
                        <span class="text-muted small text-uppercase fw-bold d-block">Monto Total Acumulado</span>
                        <h3 class="fw-bold text-success mb-0">${{ number_format($montoTotalAcumulado, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- LISTADO DE CARDS -->
        <div class="row">
            @forelse($transacciones as $tx)
                @php
                    $encomienda = $tx->encomienda;
                @endphp

                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="card border-0 shadow-sm h-100" style="background-color: var(--vanilla-cream); border: 2px solid var(--midnight-indigo) !important; border-radius: 10px;">
                        <div class="card-body d-flex flex-column justify-content-between">

                            <div>
                                <!-- Cabecera del Card: Código de rastreo y Monto -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge badge-primary px-3 py-2 fw-bold" style="background-color: var(--midnight-indigo); font-size: 0.85rem;">
                                        <i class="fas fa-barcode mr-1"></i> {{ $encomienda ? $encomienda->codigo_rastreo : 'N/D' }}
                                    </span>
                                    <span class="fw-bold text-success h5 mb-0">
                                        ${{ number_format($tx->monto, 2) }}
                                    </span>
                                </div>

                                <!-- Detalles de la Encomienda -->
                                <ul class="list-unstyled small text-secondary mb-3 mt-3">
                                    <li class="mb-2">
                                        <i class="fas fa-tag mr-1 text-primary"></i> <strong>Concepto:</strong> {{ $tx->concepto ? $tx->concepto->nombre_concepto : 'Transacción de Paquete' }}
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-shipping-fast mr-1 text-primary"></i> <strong>Pago Contraentrega:</strong>
                                        @if($encomienda && $encomienda->es_pago_contraentrega)
                                            <span class="badge badge-warning text-dark">Sí</span>
                                        @else
                                            <span class="badge badge-success">No (Pagado)</span>
                                        @endif
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-dollar-sign mr-1 text-primary"></i> <strong>Valor Declarado:</strong> ${{ $encomienda ? number_format($encomienda->valor_declarado_producto, 2) : '0.00' }}
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-user-friends mr-1 text-primary"></i> <strong>Remitente ID:</strong> {{ $encomienda ? $encomienda->id_usuario_remitente : 'N/D' }}
                                    </li>
                                </ul>
                            </div>

                            <!-- Pie del Card: Fecha de transacción -->
                            <div class="border-top pt-2 mt-2 d-flex justify-content-between align-items-center text-muted" style="font-size: 0.75rem;">
                                <span><i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($tx->fecha_transaccion)->format('d/m/Y H:i:s') }}</span>
                                <span class="badge badge-secondary">Transacción #{{ $tx->id_transaccion }}</span>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm p-5 text-center bg-white" style="border-radius: 10px;">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <h5 class="fw-bold text-secondary">No has registrado paquetes</h5>
                        <p class="text-muted small mb-0">Aún no hay transacciones asociadas a tu usuario de caja con los filtros seleccionados.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $transacciones->links() }}
        </div>
    </div>
@endsection
