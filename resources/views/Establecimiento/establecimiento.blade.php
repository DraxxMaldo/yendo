@extends('layouts.layout')

@section('titulo', 'Gestión de Establecimientos')

@push('estilos')
    {{-- CSS de DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <link rel="stylesheet" href="{{ asset('css/establecimientos.css') }}">
@endpush

@section('contenido')
    <div class="card card-custom mt-4">
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
                        <a class="dropdown-item py-2 fw-semibold" href="{{ route('establecimientos.pdf') }}">
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
        </div>

        <div class="card-body px-4 pb-4">
            <div class="table-responsive mt-2">
                {{-- ID tbl y clases para DataTables --}}
                <table id="tbl" class="table table-hover table-striped align-middle w-100 table-brand">
                    <thead>
                    <tr>
                        <th class="text-center" style="width: 3rem;">#</th>
                        <th>Nombre del Establecimiento</th>
                        <th>Zona</th>
                        <th>Departamento</th>
                        <th>Municipio</th>
                        <th>Distrito</th>
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
                                {{ $est->distrito->municipio->departamento->zona->nombre_zona ?? 'Sin Zona' }}
                            </td>
                            <td class="text-muted">
                                {{ $est->distrito->municipio->departamento->nombre_departamento ?? 'Sin Departamento' }}
                            </td>
                            <td class="text-muted">
                                {{ $est->distrito->municipio->nombre_municipio ?? 'Sin Municipio' }}
                            </td>
                            <td class="text-muted">
                                {{ $est->distrito->nombre_distrito ?? 'Sin Distrito' }}
                            </td>
                            <td class="text-muted">{{ $est->direccion_exacta }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary mr-1 btn-editar"
                                        title="Editar"
                                        data-toggle="modal"
                                        data-target="#modalEditarEstablecimiento"
                                        data-id="{{ $est->id_establecimiento }}"
                                        data-nombre="{{ $est->nombre }}"
                                        data-iddepartamento="{{ $est->distrito->municipio->id_departamento ?? '' }}"
                                        data-idmunicipio="{{ $est->distrito->id_municipio ?? '' }}"
                                        data-iddistrito="{{ $est->id_distrito }}"
                                        data-direccion="{{ $est->direccion_exacta }}"
                                        data-imagen="{{ $est->imagen ? asset('storage/' . $est->imagen) : '' }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         MODALES DE EDICIÓN Y CREACIÓN (Todos con estilo consistente)
       ========================================================================= --}}

    {{-- MODAL EDITAR ESTABLECIMIENTO --}}
    <div class="modal fade modal-modern" id="modalEditarEstablecimiento" tabindex="-1" aria-labelledby="modalEditarEstablecimientoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header px-4 py-3 modal-header-brand">
                    <h5 class="modal-title fw-bold d-flex align-items-center" id="modalEditarEstablecimientoLabel" style="color: var(--midnight-indigo);">
                        <div class="icon-circle">
                            <i class="fas fa-edit" style="font-size: 1.1rem;"></i>
                        </div>
                        <span>Editar Establecimiento</span>
                    </h5>
                    <button type="button" class="close border-0 bg-transparent" data-dismiss="modal" aria-label="Close" style="font-size: 1.25rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEditarEstablecimiento" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="modal-body px-4 py-4">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold text-secondary form-label-modern">Nombre del Establecimiento <span class="text-danger">*</span></label>
                            <div class="input-group shadow-sm input-group-modern">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0 text-muted px-3"><i class="fas fa-store"></i></span>
                                </div>
                                <input type="text" class="form-control border-left-0 pl-0 input-modern" name="nombre" id="edit_nombre" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group mb-3">
                                <label class="form-label fw-semibold text-secondary form-label-modern">Departamento <span class="text-danger">*</span></label>
                                <select class="form-control shadow-sm input-modern" id="edit_filtro_departamento">
                                    <option value="" disabled>Seleccione...</option>
                                    @foreach($departamentos as $dep)
                                        <option value="{{ $dep->id_departamento }}">{{ $dep->nombre_departamento }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label class="form-label fw-semibold text-secondary form-label-modern">Municipio <span class="text-danger">*</span></label>
                                <select class="form-control shadow-sm input-modern" name="id_municipio" id="edit_select_municipio" required>
                                    <option value="" disabled>Seleccione depto...</option>
                                    @foreach($municipios as $mun)
                                        <option value="{{ $mun->id_municipio }}" data-departamento="{{ $mun->id_departamento }}">{{ $mun->nombre_municipio }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label class="form-label fw-semibold text-secondary form-label-modern">Distrito <span class="text-danger">*</span></label>
                                <select class="form-control shadow-sm input-modern" name="id_distrito" id="edit_select_distrito" required>
                                    <option value="" disabled>Seleccione muni...</option>
                                    @foreach($distritos as $dist)
                                        <option value="{{ $dist->id_distrito }}" data-municipio="{{ $dist->id_municipio }}">{{ $dist->nombre_distrito }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold text-secondary form-label-modern">Dirección Exacta <span class="text-danger">*</span></label>
                            <textarea class="form-control shadow-sm input-modern" name="direccion_exacta" id="edit_direccion_exacta" rows="3" required style="resize: none;"></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold text-secondary form-label-modern">Actualizar Fotografía (Opcional)</label>
                            <div class="custom-file shadow-sm input-modern">
                                <input type="file" class="custom-file-input" id="customFileEditImagen" name="imagen" accept="image/*" onchange="this.nextElementSibling.innerText = this.files[0].name">
                                <label class="custom-file-label text-muted input-modern" for="customFileEditImagen">Seleccionar nueva imagen...</label>
                            </div>
                            <small class="form-text text-muted mt-1">Si no subes una nueva imagen, se conservará la anterior. Formatos: JPG, PNG, WEBP. Máx 2MB.</small>
                        </div>

                        <div class="mt-3 text-center" id="preview_container" style="display: none;">
                            <p class="text-muted small mb-1 fw-bold">Vista previa de la imagen:</p>
                            <div class="p-2 bg-white d-inline-block rounded shadow-sm border">
                                <img id="preview_image" src="" alt="Vista previa" class="img-fluid rounded" style="max-height: 150px; object-fit: cover;">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer px-4 py-3 bg-white">
                        <button type="button" class="btn btn-light px-4 fw-semibold text-muted btn-modern" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn px-4 fw-bold shadow-sm btn-modern btn-action-brand">
                            <i class="fas fa-sync-alt mr-1"></i> Actualizar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL CREAR DEPARTAMENTO --}}
    <div class="modal fade modal-modern" id="modalDepartamento" tabindex="-1" aria-labelledby="modalDepartamentoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header px-4 py-3 modal-header-brand">
                    <h5 class="modal-title fw-bold d-flex align-items-center" id="modalDepartamentoLabel" style="color: var(--midnight-indigo);">
                        <div class="icon-circle">
                            <i class="fas fa-map" style="font-size: 1.1rem;"></i>
                        </div>
                        <span>Nuevo Departamento</span>
                    </h5>
                    <button type="button" class="close border-0 bg-transparent" data-dismiss="modal" aria-label="Close" style="font-size: 1.25rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('departamentos.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="modal-body px-4 py-4">
                        <div class="form-group mb-0">
                            <label class="form-label fw-semibold text-secondary form-label-modern">Nombre del Departamento <span class="text-danger">*</span></label>
                            <div class="input-group shadow-sm input-group-modern">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0 text-muted px-3"><i class="fas fa-map"></i></span>
                                </div>
                                <input type="text" class="form-control border-left-0 pl-0 input-modern" name="nombre_departamento" placeholder="Ej. Santa Ana" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer px-4 py-3 bg-white">
                        <button type="button" class="btn btn-light px-4 fw-semibold text-muted btn-modern" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn px-4 fw-bold shadow-sm btn-modern btn-action-brand">
                            <i class="fas fa-save mr-1"></i> Guardar Departamento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL CREAR MUNICIPIO --}}
    <div class="modal fade modal-modern" id="modalMunicipio" tabindex="-1" aria-labelledby="modalMunicipioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header px-4 py-3 modal-header-brand">
                    <h5 class="modal-title fw-bold d-flex align-items-center" id="modalMunicipioLabel" style="color: var(--midnight-indigo);">
                        <div class="icon-circle">
                            <i class="fas fa-map-marker-alt" style="font-size: 1.1rem;"></i>
                        </div>
                        <span>Nuevo Municipio</span>
                    </h5>
                    <button type="button" class="close border-0 bg-transparent" data-dismiss="modal" aria-label="Close" style="font-size: 1.25rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('municipios.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="modal-body px-4 py-4">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold text-secondary form-label-modern">Departamento Perteneciente <span class="text-danger">*</span></label>
                            <select class="form-control shadow-sm input-modern" name="id_departamento" required>
                                <option value="" disabled selected>Seleccione un departamento...</option>
                                @foreach($departamentos as $dep)
                                    <option value="{{ $dep->id_departamento }}">{{ $dep->nombre_departamento }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label fw-semibold text-secondary form-label-modern">Nombre del Municipio <span class="text-danger">*</span></label>
                            <div class="input-group shadow-sm input-group-modern">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0 text-muted px-3"><i class="fas fa-map-marker-alt"></i></span>
                                </div>
                                <input type="text" class="form-control border-left-0 pl-0 input-modern" name="nombre_municipio" placeholder="Ej. Metapán" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer px-4 py-3 bg-white">
                        <button type="button" class="btn btn-light px-4 fw-semibold text-muted btn-modern" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn px-4 fw-bold shadow-sm btn-modern btn-action-brand">
                            <i class="fas fa-save mr-1"></i> Guardar Municipio
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL CREAR ESTABLECIMIENTO --}}
    <div class="modal fade modal-modern" id="modalEstablecimiento" tabindex="-1" aria-labelledby="modalEstablecimientoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header px-4 py-3 modal-header-brand">
                    <h5 class="modal-title fw-bold d-flex align-items-center" id="modalEstablecimientoLabel" style="color: var(--midnight-indigo);">
                        <div class="icon-circle">
                            <i class="fas fa-building" style="font-size: 1.1rem;"></i>
                        </div>
                        <span>Nuevo Establecimiento</span>
                    </h5>
                    <button type="button" class="close border-0 bg-transparent" data-dismiss="modal" aria-label="Close" style="font-size: 1.25rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('establecimientos.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-body px-4 py-4">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold text-secondary form-label-modern">Nombre del Establecimiento <span class="text-danger">*</span></label>
                            <div class="input-group shadow-sm input-group-modern">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0 text-muted px-3"><i class="fas fa-store"></i></span>
                                </div>
                                <input type="text" class="form-control border-left-0 pl-0 input-modern" name="nombre" placeholder="Ej. Sucursal Centro" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group mb-3">
                                <label class="form-label fw-semibold text-secondary form-label-modern">Departamento <span class="text-danger">*</span></label>
                                <select class="form-control shadow-sm input-modern" id="filtro_departamento">
                                    <option value="" disabled selected>Seleccione...</option>
                                    @foreach($departamentos as $dep)
                                        <option value="{{ $dep->id_departamento }}">{{ $dep->nombre_departamento }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label class="form-label fw-semibold text-secondary form-label-modern">Municipio <span class="text-danger">*</span></label>
                                <select class="form-control shadow-sm input-modern" name="id_municipio" id="select_municipio" required disabled>
                                    <option value="" disabled selected>Seleccione depto...</option>
                                    @foreach($municipios as $mun)
                                        <option value="{{ $mun->id_municipio }}" data-departamento="{{ $mun->id_departamento }}">{{ $mun->nombre_municipio }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label class="form-label fw-semibold text-secondary form-label-modern">Distrito <span class="text-danger">*</span></label>
                                <select class="form-control shadow-sm input-modern" name="id_distrito" id="select_distrito" required disabled>
                                    <option value="" disabled selected>Seleccione muni...</option>
                                    @foreach($distritos as $dist)
                                        <option value="{{ $dist->id_distrito }}" data-municipio="{{ $dist->id_municipio }}">{{ $dist->nombre_distrito }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold text-secondary form-label-modern">Dirección Exacta <span class="text-danger">*</span></label>
                            <textarea class="form-control shadow-sm input-modern" name="direccion_exacta" rows="3" placeholder="Detalle la dirección del establecimiento..." required style="resize: none;"></textarea>
                        </div>

                        <div class="form-group mb-0">
                            <label class="form-label fw-semibold text-secondary form-label-modern">Fotografía del Establecimiento (Opcional)</label>
                            <div class="custom-file shadow-sm input-modern">
                                <input type="file" class="custom-file-input" id="customFileImagen" name="imagen" accept="image/*" onchange="this.nextElementSibling.innerText = this.files[0].name">
                                <label class="custom-file-label text-muted input-modern" for="customFileImagen">Seleccionar archivo...</label>
                            </div>
                            <small class="form-text text-muted mt-1">Formatos permitidos: JPG, PNG, WEBP. Máximo 2MB.</small>
                        </div>
                    </div>

                    <div class="modal-footer px-4 py-3 bg-white">
                        <button type="button" class="btn btn-light px-4 fw-semibold text-muted btn-modern" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn px-4 fw-bold shadow-sm btn-modern btn-action-brand">
                            <i class="fas fa-save mr-1"></i> Guardar Establecimiento
                        </button>
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

    {{-- Script local de inicialización de DataTables --}}
    <script src="{{ asset('js/dataTable.js') }}"></script>

    {{-- Scripts de funcionamiento interno --}}
    <script src="{{ asset('js/Establecimientos/establecimiento.js') }}?v=1.0"></script>
    <script src="{{ asset('js/Establecimientos/actualizar-establecimiento.js') }}?v=1.0"></script>
@endpush
