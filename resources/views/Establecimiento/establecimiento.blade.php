@extends('layouts.layout')

@section('titulo', 'Gestión de Establecimientos')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/establecimientos.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/Establecimientos/establecimiento.js') }}?v=1.0"></script>
@endpush

@section('contenido')
    <div class="card mt-4 shadow-sm border-0">
        <div class="card-header bg-white pt-4 pb-3 px-4 border-0 d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
            <h3 class="card-title fw-bold m-0" style="color: var(--midnight-indigo); font-size: 1.5rem;">
                <i class="fas fa-building mr-2"></i> Establecimientos
            </h3>

            {{-- CONTENEDOR DE NUEVOS BOTONES DOBLES --}}
            <div class="d-flex flex-wrap align-items-stretch" style="gap: 10px;">

                <!-- Botón Desplegable Compartir -->
                <div class="dropdown d-flex">
                    <button class="btn text-white fw-bold d-flex align-items-center justify-content-center px-4"
                            type="button"
                            id="dropdownCompartir"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            style="background-color: var(--midnight-indigo); border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <i class="fas fa-share-alt mr-2"></i> Compartir
                    </button>
                    <div class="dropdown-menu shadow-sm border-0 mt-2" aria-labelledby="dropdownCompartir" style="border-radius: 10px;">
                        <a class="dropdown-item py-2 fw-semibold" href="{{ route('establecimientos.excel') }}" id="btnExportarExcel">
                            <i class="fas fa-file-excel text-success mr-2" style="width: 20px;"></i> Exportar Excel
                        </a>
                        <a class="dropdown-item py-2 fw-semibold" href="#">
                            <i class="fas fa-file-pdf text-danger mr-2" style="width: 20px;"></i> Exportar PDF
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item py-2 fw-semibold" href="#">
                            <i class="fas fa-envelope text-primary mr-2" style="width: 20px;"></i> Mandar por Gmail
                        </a>
                    </div>
                </div>

                <!-- Botón Agregar Establecimiento -->
                <button class="btn-agregar-doble" data-toggle="modal" data-target="#modalEstablecimiento">
                    <i class="fas fa-plus"></i>
                    <div class="texto-btn">
                        <span>Agregar nuevo</span>
                        <span>establecimiento</span>
                    </div>
                </button>

                <!-- Botón Agregar Departamento -->
                <button class="btn-agregar-doble" data-toggle="modal" data-target="#modalDepartamento">
                    <i class="fas fa-plus"></i>
                    <div class="texto-btn">
                        <span>Agregar nuevo</span>
                        <span>Departamento</span>
                    </div>
                </button>

                <!-- Botón Agregar Municipio -->
                <button class="btn-agregar-doble" data-toggle="modal" data-target="#modalMunicipio">
                    <i class="fas fa-plus"></i>
                    <div class="texto-btn">
                        <span>Agregar nuevo</span>
                        <span>Municipio</span>
                    </div>
                </button>
            </div>

        <div class="card-body px-4 pb-4">
            <div class="table-responsive mt-2">
                <table class="table table-hover table-striped align-middle w-100">
                    <thead class="table-dark">
                    <tr>
                        <th class="text-center" style="width: 3rem;">#</th>
                        <th>Nombre del Establecimiento</th>
                        <th>Departamento</th>
                        <th>Municipio</th>
                        <th>Dirección Exacta</th>
                        <th class="text-center" style="width: 8rem;">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($establecimientos as $est)
                        <tr>
                            <td class="text-center text-muted fw-bold">{{ $est->id_establecimiento }}</td>
                            <td class="fw-bold text-dark">{{ $est->nombre }}</td>

                            <td class="text-muted">
                                {{ $est->municipio->departamento->nombre_departamento ?? 'Sin Departamento' }}
                            </td>

                            {{-- Mostramos el nombre del municipio a través de la relación --}}
                            <td class="text-muted">
                                {{ $est->municipio->nombre_municipio ?? 'Sin Municipio' }}
                            </td>




                            <td class="text-muted">{{ $est->direccion_exacta }}</td>

                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary mr-1" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="fas fa-building mb-3" style="font-size: 3rem; opacity: 0.5;"></i><br>
                                No hay establecimientos registrados en el sistema.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         MODALES DE CREACIÓN
       ========================================================================= --}}

    {{-- 1. MODAL DEPARTAMENTO --}}
    <div class="modal fade" id="modalDepartamento" tabindex="-1" aria-labelledby="modalDepartamentoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; border: none;">
                <div class="modal-header" style="background-color: var(--vanilla-cream); border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title fw-bold" id="modalDepartamentoLabel" style="color: var(--midnight-indigo);">
                        <i class="fas fa-map mr-2"></i> Nuevo Departamento
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('departamentos.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="modal-body px-4 py-4">
                        <div class="form-group">
                            <label class="fw-bold text-muted">Nombre del Departamento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombre_departamento" placeholder="Ej. Santa Ana" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light" style="border-radius: 0 0 15px 15px;">
                        <button type="button" class="btn btn-secondary fw-bold" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary fw-bold" style="background-color: var(--midnight-indigo); border-color: var(--midnight-indigo);">Guardar Departamento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 2. MODAL MUNICIPIO --}}
    <div class="modal fade" id="modalMunicipio" tabindex="-1" aria-labelledby="modalMunicipioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; border: none;">
                <div class="modal-header" style="background-color: var(--vanilla-cream); border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title fw-bold" id="modalMunicipioLabel" style="color: var(--midnight-indigo);">
                        <i class="fas fa-map-marker-alt mr-2"></i> Nuevo Municipio
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('municipios.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="modal-body px-4 py-4">
                        <div class="form-group mb-4">
                            <label class="fw-bold text-muted">Departamento Perteneciente <span class="text-danger">*</span></label>
                            <select class="form-control" name="id_departamento" required>
                                <option value="" disabled selected>Seleccione un departamento...</option>

                                {{-- Iteramos dinámicamente los departamentos que enviaste desde el controlador --}}
                                @foreach($departamentos as $dep)
                                    <option value="{{ $dep->id_departamento }}">{{ $dep->nombre_departamento }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="form-group">
                            <label class="fw-bold text-muted">Nombre del Municipio <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombre_municipio" placeholder="Ej. Metapán" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light" style="border-radius: 0 0 15px 15px;">
                        <button type="button" class="btn btn-secondary fw-bold" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary fw-bold" style="background-color: var(--midnight-indigo); border-color: var(--midnight-indigo);">Guardar Municipio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 3. MODAL ESTABLECIMIENTO --}}
    <div class="modal fade" id="modalEstablecimiento" tabindex="-1" aria-labelledby="modalEstablecimientoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; border: none;">
                <div class="modal-header" style="background-color: var(--vanilla-cream); border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title fw-bold" id="modalEstablecimientoLabel" style="color: var(--midnight-indigo);">
                        <i class="fas fa-building mr-2"></i> Nuevo Establecimiento
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('establecimientos.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="modal-body px-4 py-4">
                        {{-- Campo de Nombre (ancho completo) --}}
                        <div class="form-group mb-4">
                            <label class="fw-bold text-muted">Nombre del Establecimiento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombre" placeholder="Ej. Sucursal Centro" required>
                        </div>

                        <div class="row">
                            {{-- Nuevo campo: Seleccionar Departamento (no se envía a la BD, solo sirve para filtrar) --}}
                            <div class="col-md-6 form-group mb-4">
                                <label class="fw-bold text-muted">Departamento <span class="text-danger">*</span></label>
                                <select class="form-control" id="filtro_departamento">
                                    <option value="" disabled selected>Seleccione un departamento...</option>
                                    @foreach($departamentos as $dep)
                                        <option value="{{ $dep->id_departamento }}">{{ $dep->nombre_departamento }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Campo de Municipio (Deshabilitado por defecto hasta que se elija departamento) --}}
                            <div class="col-md-6 form-group mb-4">
                                <label class="fw-bold text-muted">Municipio Perteneciente <span class="text-danger">*</span></label>
                                <select class="form-control" name="id_municipio" id="select_municipio" required disabled>
                                    <option value="" disabled selected>Primero seleccione un departamento...</option>

                                    {{-- Iteramos los municipios agregándoles el "data-departamento" --}}
                                    @foreach($municipios as $mun)
                                        <option value="{{ $mun->id_municipio }}" data-departamento="{{ $mun->id_departamento }}">
                                            {{ $mun->nombre_municipio }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="fw-bold text-muted">Dirección Exacta <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="direccion_exacta" rows="3" placeholder="Detalle la dirección del establecimiento..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light" style="border-radius: 0 0 15px 15px;">
                        <button type="button" class="btn btn-secondary fw-bold" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary fw-bold" style="background-color: var(--midnight-indigo); border-color: var(--midnight-indigo);">Guardar Establecimiento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
