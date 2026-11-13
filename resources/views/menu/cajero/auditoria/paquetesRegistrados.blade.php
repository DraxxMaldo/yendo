@extends('layouts.layout')

@section('titulo', 'Auditoría de Paquetes | Yendo')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
    <link rel="stylesheet" href="{{ asset('css/paquetes-registrados.css') }}">

@endpush

@section('contenido')
    @include('layouts.toast')

    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold" style="color: var(--midnight-indigo);">
                    <i class="fas fa-clipboard-list mr-2" style="color: var(--accent-primary);"></i> Auditoría de Paquetes Registrados
                </h2>
                <p class="text-muted mb-0">Monitoreo y trazabilidad de transacciones financieras registradas por cada cajero en el sistema.</p>
            </div>
        </div>

        <div class="row">
            @forelse($transacciones as $tx)
                @php
                    // Obtenemos el perfil de la persona directamente usando el ID del cajero de la transacción
                    $perfilCajero = \App\Models\PerfilPersona::find($tx->id_usuario_cajero);
                    $nombreCajero = $perfilCajero ? ($perfilCajero->nombres . ' ' . $perfilCajero->apellidos) : 'Cajero ID #' . $tx->id_usuario_cajero;

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

                                <!-- Información del Cajero Responsable (Desde PerfilPersona) -->
                                <div class="mb-3 p-2 rounded bg-white border">
                                    <small class="text-muted text-uppercase fw-bold d-block" style="font-size: 0.7rem;">Cajero Responsable</small>
                                    <span class="fw-bold text-dark">
                                        <i class="fas fa-user-tie text-primary mr-1"></i> {{ $nombreCajero }}
                                    </span>
                                    <div class="text-muted small">ID de Usuario: #{{ $tx->id_usuario_cajero }}</div>
                                </div>

                                <!-- Detalles de la Encomienda -->
                                <ul class="list-unstyled small text-secondary mb-3">
                                    <li class="mb-1">
                                        <i class="fas fa-tag mr-1 text-primary"></i> <strong>Concepto:</strong> {{ $tx->concepto ? $tx->concepto->nombre_concepto : 'Transacción de Paquete' }}
                                    </li>
                                    <li class="mb-1">
                                        <i class="fas fa-shipping-fast mr-1 text-primary"></i> <strong>Pago Contraentrega:</strong>
                                        @if($encomienda && $encomienda->es_pago_contraentrega)
                                            <span class="badge badge-warning text-dark">Sí</span>
                                        @else
                                            <span class="badge badge-success">No (Pagado)</span>
                                        @endif
                                    </li>
                                    <li class="mb-1">
                                        <i class="fas fa-dollar-sign mr-1 text-primary"></i> <strong>Valor Declarado:</strong> ${{ $encomienda ? number_format($encomienda->valor_declarado_producto, 2) : '0.00' }}
                                    </li>
                                </ul>
                            </div>

                            <!-- Pie del Card: Fecha de transacción -->
                            <div class="border-top pt-2 mt-2 d-flex justify-content-between align-items-center text-muted" style="font-size: 0.75rem;">
                                <span><i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($tx->fecha_transaccion)->format('d/m/Y H:i:s') }}</span>
                                <span class="badge badge-secondary">ID Transacción: #{{ $tx->id_transaccion }}</span>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm p-5 text-center bg-white" style="border-radius: 10px;">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <h5 class="fw-bold text-secondary">No hay transacciones registradas</h5>
                        <p class="text-muted small mb-0">Aún no se han procesado encomiendas que generen registros en la auditoría.</p>
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
