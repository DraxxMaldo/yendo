@extends('layouts.layout')

@section('titulo', 'Gestión de Empleados y Sucursales | Yendo')

@push('estilos')
    {{-- CSS de DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
    <link rel="stylesheet" href="{{ asset('css/empleados-sucursales.css') }}">
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
                <button type="button" class="btn btn-brand fw-bold rounded-pill px-4" data-toggle="modal" data-target="#modalSpaPersonal" style="background-color: var(--accent-tertiary); color: var(--midnight-dark); border: 2px solid var(--midnight-dark);">
                    <i class="fas fa-user-plus mr-1"></i> Nuevo Personal
                </button>
            </div>

            <div class="table-responsive bg-white p-3 rounded border border-dark mt-3">
                <table id="tbl" class="table table-hover align-middle mb-0 w-100">
                    <thead class="thead-dark" style="background-color: var(--midnight-indigo); color: var(--vanilla-cream);">
                    <tr>
                        <th>ID Usuario</th>
                        <th>Nombre del Empleado</th>
                        <th>Correo Electrónico</th>
                        <th>Establecimiento Asignado</th>
                        <th class="text-center">Rol</th>
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
                                <span class="badge px-3 py-2" style="background-color: var(--midnight-indigo); color: var(--vanilla-cream);">
                                    {{ $emp->credenciales->rol->nombre_rol ?? 'Personal' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- MODAL SPA MULTIPASOS PARA NUEVO PERSONAL -->
    <div class="modal fade" id="modalSpaPersonal" tabindex="-1" aria-labelledby="modalSpaPersonalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="background-color: var(--vanilla-cream); color: var(--midnight-indigo); border: 2px solid var(--midnight-indigo) !important; border-radius: 8px;">

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalSpaPersonalLabel">
                        <i class="fas fa-user-shield mr-2" style="color: var(--accent-primary);"></i> Registrar Nuevo Personal - Asistente SPA
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: var(--midnight-indigo);">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('empleados.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="modal-body p-4">

                        <!-- PASO 1: SELECCIÓN DE ROL -->
                        <div id="paso-rol" class="text-center py-3">
                            <h4 class="fw-bold mb-3">Paso 1: Seleccione el Rol del Personal</h4>
                            <p class="text-muted mb-4">Elija el rol operativo que desempeñará el nuevo usuario dentro de la plataforma.</p>

                            <div class="row justify-content-center">
                                <div class="col-md-4 mb-3">
                                    <div class="card role-card p-3 border-dark cursor-pointer shadow-sm" data-role="4" style="border-radius: 8px; transition: all 0.2s; cursor: pointer;">
                                        <i class="fas fa-cash-register fa-2x mb-2" style="color: var(--midnight-indigo);"></i>
                                        <h5 class="fw-bold">Cajero</h5>
                                        <small class="text-muted">Registro de encomiendas y cobros.</small>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card role-card p-3 border-dark cursor-pointer shadow-sm" data-role="5" style="border-radius: 8px; transition: all 0.2s; cursor: pointer;">
                                        <i class="fas fa-boxes fa-2x mb-2" style="color: var(--midnight-indigo);"></i>
                                        <h5 class="fw-bold">Logística</h5>
                                        <small class="text-muted">Coordinación de envíos y paquetes.</small>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card role-card p-3 border-dark cursor-pointer shadow-sm" data-role="6" style="border-radius: 8px; transition: all 0.2s; cursor: pointer;">
                                        <i class="fas fa-motorcycle fa-2x mb-2" style="color: var(--midnight-indigo);"></i>
                                        <h5 class="fw-bold">Repartidor</h5>
                                        <small class="text-muted">Entrega de paquetes a destino.</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Input oculto para almacenar el rol seleccionado -->
                            <input type="hidden" name="id_rol" id="inputRolSeleccionado" value="{{ old('id_rol') }}" required>

                            <div class="text-center mt-4">
                                <button type="button" id="btnSiguientePaso" class="btn btn-brand fw-bold rounded-pill px-5 py-2" style="background-color: var(--midnight-indigo); color: var(--vanilla-cream);" disabled>
                                    Siguiente <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- PASO 2: DATOS Y CORREO -->
                        <div id="paso-datos" class="py-3" style="display: none;">
                            <h4 class="fw-bold mb-2">Paso 2: Datos y Credenciales</h4>
                            <p class="text-muted mb-4">Ingrese el correo electrónico e indique el establecimiento al que estará vinculado el empleado.</p>

                            <div class="mb-3">
                                <label class="fw-bold small">Correo Electrónico del Empleado</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" name="correo_electronico" class="form-control @error('correo_electronico') is-invalid @enderror" value="{{ old('correo_electronico') }}" placeholder="correo@ejemplo.com" required>
                                </div>
                                <small class="text-muted">Se le enviará un correo electrónico con sus credenciales de acceso.</small>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold small">Establecimiento Asignado</label>
                                <select name="id_establecimiento" class="form-control @error('id_establecimiento') is-invalid @enderror" required {{ $establecimientos->isEmpty() ? 'disabled' : '' }}>
                                    <option value="" disabled selected>Seleccione un establecimiento...</option>
                                    @forelse($establecimientos as $est)
                                        <option value="{{ $est->id_establecimiento }}" {{ old('id_establecimiento') == $est->id_establecimiento ? 'selected' : '' }}>
                                            {{ $est->nombre }}
                                        </option>
                                    @empty
                                        <option value="" disabled selected>No tienes establecimientos creados</option>
                                    @endforelse
                                </select>

                                @if($establecimientos->isEmpty())
                                    <small class="text-danger mt-1 d-block font-weight-bold">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Necesitas registrar al menos un establecimiento tuyo antes de asignar personal.
                                        <a href="{{ route('establecimientos.index') }}" class="text-dark" style="text-decoration: underline;">Ir al panel de establecimientos</a>
                                    </small>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="button" id="btnAnteriorPaso" class="btn btn-outline-secondary fw-bold rounded-pill px-4">Anterior</button>
                                <button type="submit" class="btn btn-success fw-bold rounded-pill px-4" {{ $establecimientos->isEmpty() ? 'disabled' : '' }}>
                                    <i class="fas fa-paper-plane mr-1"></i> Enviar Credenciales y Crear
                                </button>
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {{-- JS de DataTables --}}
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('js/dataTable.js') }}"></script>

    {{-- Script independiente para la gestión del modal de personal (verifica que el nombre coincida exactamente con tu carpeta public/js/Afiliado/) --}}
    <script src="{{ asset('js/Afiliado/SPA-Seleccionar-Rol.js') }}"></script>

    <script>
        // Invocamos la función de errores pasando los parámetros con directivas JSON seguras de Blade
        document.addEventListener('DOMContentLoaded', function () {
            inicializarModalErrores(
                @json($errors->any()),
                @json($errors->has('correo_electronico') || $errors->has('id_establecimiento'))
            );
        });
    </script>
@endpush
