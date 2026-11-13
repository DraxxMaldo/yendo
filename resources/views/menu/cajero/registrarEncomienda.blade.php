@extends('layouts.layout')

@section('titulo', 'Registrar Encomienda | Yendo')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
@endpush

@section('contenido')
    @include('layouts.toast')

    <div class="container-fluid py-4">
        <div class="card border-0 shadow-lg p-4" style="background-color: var(--vanilla-cream); border: 2px solid var(--midnight-indigo) !important; border-radius: 10px;">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold" style="color: var(--midnight-indigo);">
                        <i class="fas fa-box-open mr-2" style="color: var(--accent-primary);"></i> Registrar Nueva Encomienda
                    </h2>
                    <p class="text-muted mb-0">Módulo de operaciones de caja para el ingreso y despacho de paquetes.</p>
                </div>
            </div>

            <form action="{{ route('encomiendas.store') }}" method="POST">
                @csrf

                <div class="row">

                    <!-- Código de Rastreo (Generado automáticamente y bloqueado) -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold small text-uppercase">Código de Rastreo (Sistema)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light"><i class="fas fa-barcode text-primary"></i></span>
                            </div>
                            <input type="text" name="codigo_rastreo" class="form-control bg-light fw-bold @error('codigo_rastreo') is-invalid @enderror"
                                   value="{{ old('codigo_rastreo', $codigoSugerido) }}" readonly required>
                        </div>
                        <small class="text-muted">Este código es único y generado de forma automática por seguridad.</small>
                        @error('codigo_rastreo') <small class="text-danger d-block">{{ $message }}</small> @enderror
                    </div>

                    <!-- Usuario Remitente -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold small text-uppercase">Remitente</label>
                        <select name="id_usuario_remitente" class="form-control @error('id_usuario_remitente') is-invalid @enderror" required>
                            <option value="" disabled selected>Seleccione el remitente...</option>
                            @foreach($remitentes as $rem)
                                <option value="{{ $rem->id_usuario }}" {{ old('id_usuario_remitente') == $rem->id_usuario ? 'selected' : '' }}>
                                    {{ $rem->correo_electronico }} (ID: #US-{{ str_pad($rem->id_usuario, 5, '0', STR_PAD_LEFT) }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_usuario_remitente') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Establecimiento Origen -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold small text-uppercase">Establecimiento de Origen (Tu Sucursal)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light"><i class="fas fa-building text-primary"></i></span>
                            </div>
                            <input type="text" class="form-control bg-light fw-bold"
                                   value="{{ $establecimientoOrigen ? $establecimientoOrigen->nombre : 'Sin establecimiento asignado' }}"
                                   readonly disabled>
                        </div>
                        @if(!$establecimientoOrigen)
                            <small class="text-danger mt-1 d-block font-weight-bold">
                                <i class="fas fa-exclamation-triangle mr-1"></i> No estás vinculado a ninguna sucursal.
                            </small>
                        @endif
                    </div>

                    <!-- Establecimiento Destino -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold small text-uppercase">Establecimiento de Destino</label>
                        <select name="id_establecimiento_destino" class="form-control @error('id_establecimiento_destino') is-invalid @enderror">
                            <option value="" selected>Seleccione destino (Opcional)...</option>
                            @foreach($establecimientos as $est)
                                <option value="{{ $est->id_establecimiento }}" {{ old('id_establecimiento_destino') == $est->id_establecimiento ? 'selected' : '' }}>
                                    {{ $est->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_establecimiento_destino') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Tipo de Envío -->
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold small text-uppercase">Tipo de Envío</label>
                        <select name="id_tipo_envio" class="form-control @error('id_tipo_envio') is-invalid @enderror" required>
                            <option value="" disabled>Seleccione tipo...</option>
                            @foreach($tiposEnvio as $tipo)
                                <option value="{{ $tipo->id_tipo_envio }}"
                                    {{ old('id_tipo_envio', $idTipoSucursalXSucursal) == $tipo->id_tipo_envio ? 'selected' : '' }}>
                                    {{ $tipo->nombre_tipo }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_tipo_envio') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Catálogo de Precios (Prenda / Servicio) -->
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold small text-uppercase">Prenda / Servicio (Catálogo)</label>
                        <select name="id_precio" class="form-control @error('id_precio') is-invalid @enderror" required>
                            <option value="" disabled selected>Seleccione tarifa...</option>
                            @foreach($preciosCatalogo as $precioItem)
                                <option value="{{ $precioItem->id_precio }}" {{ old('id_precio') == $precioItem->id_precio ? 'selected' : '' }}>
                                    {{ $precioItem->nombre_prenda }} (${{ number_format($precioItem->precio, 2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_precio') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Valor Declarado -->
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold small text-uppercase">Valor Declarado del Producto ($)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white">$</span>
                            </div>
                            <input type="number" step="0.01" min="0" name="valor_declarado_producto" class="form-control @error('valor_declarado_producto') is-invalid @enderror" value="{{ old('valor_declarado_producto', '0.00') }}" required>
                        </div>
                        @error('valor_declarado_producto') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Pago Contraentrega -->
                    <div class="col-md-12 mb-3">
                        <label class="fw-bold small text-uppercase d-block">¿Es Pago Contraentrega?</label>
                        <div class="mt-2">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="contraentrega_si" name="es_pago_contraentrega" value="1" class="custom-control-input" {{ old('es_pago_contraentrega') == '1' ? 'checked' : '' }}>
                                <label class="custom-control-label fw-bold" for="contraentrega_si">Sí (Se suma la tarifa + el valor declarado al cobro)</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="contraentrega_no" name="es_pago_contraentrega" value="0" class="custom-control-input" {{ old('es_pago_contraentrega', '0') == '0' ? 'checked' : '' }}>
                                <label class="custom-control-label fw-bold" for="contraentrega_no">No (Pagado previamente, solo se cobra la tarifa)</label>
                            </div>
                        </div>
                        @error('es_pago_contraentrega') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Fecha de Ingreso (Oculta o visible según prefieras, la ponemos por defecto con la fecha actual) -->
                    <input type="hidden" name="fecha_ingreso" value="{{ now() }}">

                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn fw-bold rounded-pill px-5 py-2 shadow-sm" style="background-color: var(--midnight-indigo); color: var(--vanilla-cream);">
                        <i class="fas fa-save mr-1"></i> Guardar y Registrar Encomienda
                    </button>
                </div>

            </form>

        </div>
    </div>
@endsection
