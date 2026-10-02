@extends('layouts.layout')

@section('titulo', 'Gestión de Usuarios')

@push('estilos')
    {{-- CSS de DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    {{-- Estilos específicos de la marca para esta vista --}}
    <link rel="stylesheet" href="{{ asset('css/usuarios.css') }}">

    {{-- Animaciones y posiciones personalizadas para los Toasts --}}
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
@endpush

@section('contenido')
    {{-- Contenedor Principal (Tarjeta) --}}
    <div class="card card-custom mt-4">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-4 pb-2 px-4">
            <h3 class="card-title fw-bold" style="color: var(--midnight-indigo); font-size: 1.5rem;">
                <i class="fas fa-user-friends mr-2"></i> Usuarios del Sistema
            </h3>
            <button type="button" class="btn btn-brand fw-bold rounded-pill px-4" data-toggle="modal" data-target="#modalUsuario">
                <i class="fas fa-plus mr-1"></i> Nuevo Usuario
            </button>
        </div>

        <div class="card-body px-4 pb-4">
            <div class="table-responsive mt-2">
                <table id="tbl" class="table table-hover table-striped align-middle w-100 table-brand">
                    <thead>
                    <tr>
                        <th class="text-center" style="width: 3rem;">#</th>
                        <th>Nombre Completo</th>
                        <th>Contacto</th>
                        <th>Rol</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center" style="width: 8rem;">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($usuarios))
                        @foreach ($usuarios as $u)
                            <tr>
                                <td class="text-center text-muted fw-bold">{{ $u->id_usuario }}</td>
                                <td>
                                    <span class="fw-bold text-dark">{{ $u->perfil->nombres ?? 'N/A' }} {{ $u->perfil->apellidos ?? '' }}</span>
                                </td>
                                <td>
                                    <span class="d-block text-dark"><i class="fas fa-envelope text-muted mr-1"></i> {{ $u->correo_electronico }}</span>
                                    <small class="text-muted"><i class="fas fa-phone mr-1"></i> {{ $u->perfil->telefono ?? 'Sin teléfono' }}</small>
                                </td>
                                <td>
                                    <span class="badge" style="background-color: var(--vanilla-cream); color: var(--midnight-indigo); border: 1px solid var(--midnight-indigo);">
                                        {{ $u->rol->nombre_rol ?? 'Sin rol' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if ($u->estado_cuenta)
                                        <span class="badge badge-success px-2 py-1">Activo</span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary mr-1" data-toggle="modal" data-target="#modalEditarUsuario" onclick="editarUsuario({{ $u->id_usuario }})" title="Editar Usuario">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#modalEliminarUsuario" onclick="eliminarUsuario({{ $u->id_usuario }}, '{{ $u->perfil->nombres ?? 'Usuario' }}')" title="Eliminar Usuario">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL REGISTRAR USUARIO -->
    <div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0" style="background-color: var(--midnight-indigo); color: var(--vanilla-cream);">
                    <h5 class="modal-title fw-bold" id="modalUsuarioLabel">
                        <i class="fas fa-user-plus mr-2" style="color: var(--vanilla-cream);"></i> Registrar Nuevo Usuario
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: var(--vanilla-cream); text-shadow: none; opacity: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="formUsuario" action="{{ route('usuarios.insertar') }}" method="POST" novalidate>
                    @csrf
                    <div class="modal-body row pt-4 px-4">
                        <div class="col-md-6 mb-3">
                            <label for="nombres" class="form-label text-muted small fw-bold text-uppercase">Nombres</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white text-muted"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control border-left-0" id="nombres" name="nombres" value="{{ old('nombres') }}" required maxlength="100">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="apellidos" class="form-label text-muted small fw-bold text-uppercase">Apellidos</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white text-muted"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control border-left-0" id="apellidos" name="apellidos" value="{{ old('apellidos') }}" required maxlength="100">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label text-muted small fw-bold text-uppercase">Teléfono</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white text-muted"><i class="fas fa-phone"></i></span>
                                </div>
                                <input type="text" class="form-control border-left-0" id="telefono" name="telefono" value="{{ old('telefono') }}" required maxlength="20">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="correo_electronico" class="form-label text-muted small fw-bold text-uppercase">Correo Electrónico</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white text-muted"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" class="form-control border-left-0" id="correo_electronico" name="correo_electronico" value="{{ old('correo_electronico') }}" required maxlength="150">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="contrasenha" class="form-label text-muted small fw-bold text-uppercase">Contraseña</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white text-muted"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" class="form-control border-left-0" id="contrasenha" name="contrasenha" required minlength="8">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="id_rol" class="form-label text-muted small fw-bold text-uppercase">Asignar Rol</label>
                            <select class="form-control" id="id_rol" name="id_rol" required>
                                <option value="" disabled {{ old('id_rol') ? '' : 'selected' }}>Seleccione un rol...</option>
                                @if(isset($roles))
                                    @foreach ($roles as $rol)
                                        <option value="{{ $rol->id_rol }}" {{ old('id_rol') == $rol->id_rol ? 'selected' : '' }}>{{ $rol->nombre_rol }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4">
                        <button type="button" class="btn btn-outline-secondary fw-bold rounded-pill px-4" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-brand fw-bold rounded-pill px-4">
                            <i class="fas fa-save mr-1"></i> Guardar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDITAR USUARIO -->
    <div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0" style="background-color: var(--midnight-indigo); color: var(--vanilla-cream);">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-edit mr-2" style="color: var(--vanilla-cream);"></i> Editar Usuario
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: var(--vanilla-cream); opacity: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-5">
                    <p class="text-muted">El formulario de edición se programará en la siguiente fase.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL ELIMINAR USUARIO -->
    <div class="modal fade" id="modalEliminarUsuario" tabindex="-1" aria-labelledby="modalEliminarUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title fw-bold" id="modalEliminarUsuarioLabel">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Confirmar Eliminación
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEliminar" method="POST" action="#">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body p-4 text-center">
                        <i class="fas fa-trash-alt text-danger mb-3" style="font-size: 3rem;"></i>
                        <h5 class="mb-3">¿Estás seguro de que deseas eliminar este usuario?</h5>
                        <p class="mb-1 text-dark">El usuario <strong id="nombreEliminar" style="color: var(--midnight-indigo);"></strong> será desactivado/eliminado del sistema.</p>
                        <p class="text-muted small mb-0">Esta acción podría ser irreversible.</p>
                    </div>
                    <div class="modal-footer border-0 d-flex justify-content-center pb-4">
                        <button type="button" class="btn btn-outline-secondary fw-bold rounded-pill px-4 mx-2" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger fw-bold rounded-pill px-4 mx-2">Eliminar Definitivamente</button>
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
    {{-- Script local de inicialización --}}
    <script src="{{ asset('js/dataTable.js') }}"></script>

    <script>
        function editarUsuario(id) {
            console.log("Abriendo modal de edición para el usuario ID:", id);
        }

        function eliminarUsuario(id, nombre) {
            console.log("Preparando eliminación para el usuario ID:", id);
            $('#nombreEliminar').text(nombre);
        }

        // Reabrir el modal automáticamente si hay errores de validación
        @if($errors->any())
        $(document).ready(function() {
            $('#modalUsuario').modal('show');
        });
        @endif
    </script>
@endpush
