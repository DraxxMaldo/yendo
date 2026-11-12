@extends('layouts.layout')

@section('titulo', 'Mi Perfil | Yendo')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
    {{-- Cropper.js CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
@endpush

@section('contenido')
    <div class="container-fluid py-4" id="perfil-contenedor-principal">

        {{-- ENCABEZADO --}}
        <header class="perfil-reveal mb-4" id="perfil-hero-banner" style="--i:0">
            <div>
                <span class="badge-brutalist-pill" id="perfil-hero-badge">Configuración de cuenta</span>
                <h1 id="perfil-titulo-pagina">Mi Perfil</h1>
                <p id="perfil-subtitulo-pagina">Administra tu identidad, credenciales y datos de tu cuenta en Yendo.</p>
            </div>
            <div id="perfil-status-box" class="status-indicator-box">
                <i class="fas fa-shield-alt" aria-hidden="true"></i>
                <span>Sesión segura &amp; verificada</span>
            </div>
        </header>

        <div class="perfil-layout">

            {{-- COLUMNA IZQUIERDA: Identidad, avatar y rol --}}
            <aside class="perfil-reveal" style="--i:1">
                <section class="card perfil-tarjeta perfil-tarjeta--oscura" id="perfil-tarjeta-identidad" aria-labelledby="perfil-nombre-corto">
                    <div class="perfil-tarjeta-cuerpo">

                        {{-- CONTENEDOR Y FORMULARIO TRADICIONAL PARA LA FOTO DE PERFIL --}}
                        <div class="perfil-avatar-contenedor" id="perfil-avatar-contenedor">
                            <form id="formFotoPerfil" action="{{ route('perfil.actualizar_foto') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                {{-- Generamos el avatar dinámicamente usando el nombre real del usuario --}}
                                <img src="{{ $datosUsuario['imagen'] }}"
                                     alt="Avatar de {{ $datosUsuario['nombres'] }}" class="avatar-brutalist" id="perfil-avatar-img" width="160" height="160">

                                {{-- Input file vinculado al formulario tradicional --}}
                                <input type="file" id="avatar-input-file" name="imagen" class="visually-hidden sr-only" accept="image/*">

                                <label for="avatar-input-file" class="avatar-upload-badge" title="Cambiar foto de perfil">
                                    <i class="fas fa-camera" aria-hidden="true"></i>
                                    <span class="visually-hidden sr-only">Cambiar foto de perfil</span>
                                </label>
                            </form>
                        </div>

                        {{-- Nombre real --}}
                        <h2 id="perfil-nombre-corto">{{ $datosUsuario['nombres'] }}</h2>

                        {{-- Rol real --}}
                        <span class="perfil-rol-badge" id="perfil-rol-badge">
                            <i class="fas fa-crown" aria-hidden="true"></i> {{ $datosUsuario['rol'] }}
                        </span>

                        <div class="perfil-status-pill" id="perfil-estado-cuenta">
                            <span class="status-dot animate-pulse" aria-hidden="true"></span> Cuenta activa &amp; verificada
                        </div>

                        {{-- ID de usuario real --}}
                        <p class="perfil-id-usuario">ID de usuario: <strong>#US-{{ str_pad($datosUsuario['id_usuario'], 5, '0', STR_PAD_LEFT) }}</strong></p>
                    </div>
                </section>
            </aside>

            {{-- COLUMNA DERECHA: Información personal y seguridad --}}
            <div class="perfil-columna">

                {{-- Información personal --}}
                <section class="card perfil-tarjeta perfil-tarjeta--clara perfil-reveal" id="perfil-tarjeta-informacion" style="--i:2" aria-labelledby="perfil-subtitulo-info">
                    <div class="perfil-tarjeta-cuerpo">
                        <div class="perfil-tarjeta-cabecera">
                            <h3 class="perfil-subtitulo" id="perfil-subtitulo-info">
                                <i class="fas fa-user-edit perfil-icono" aria-hidden="true"></i>Información personal
                            </h3>
                            <span id="perfil-modo-badge" data-mode="read" role="status" aria-live="polite">Modo Lectura</span>
                        </div>

                        {{-- Formulario para actualizar información de texto --}}
                        <form id="perfil-form-informacion" action="{{ route('perfil.actualizar_informacion') }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="perfil-campos">
                                <div>
                                    <label class="perfil-label" for="perfil-input-nombres">Nombres</label>
                                    <div class="input-group-brutalist">
                                        <span class="input-icon"><i class="fas fa-user" aria-hidden="true"></i></span>
                                        <input type="text" id="perfil-input-nombres" name="nombres" class="form-control perfil-input" value="{{ old('nombres', $datosUsuario['nombres']) }}" autocomplete="given-name" readonly>
                                    </div>
                                </div>

                                <div>
                                    <label class="perfil-label" for="perfil-input-apellidos">Apellidos</label>
                                    <div class="input-group-brutalist">
                                        <span class="input-icon"><i class="fas fa-user-tag" aria-hidden="true"></i></span>
                                        <input type="text" id="perfil-input-apellidos" name="apellidos" class="form-control perfil-input" value="{{ old('apellidos', $datosUsuario['apellidos']) }}" autocomplete="family-name" readonly>
                                    </div>
                                </div>

                                <div>
                                    <label class="perfil-label" for="perfil-input-correo">Correo electrónico</label>
                                    <div class="input-group-brutalist">
                                        <span class="input-icon"><i class="fas fa-envelope" aria-hidden="true"></i></span>
                                        <input type="email" id="perfil-input-correo" class="form-control perfil-input" value="{{ $datosUsuario['correo'] }}" aria-describedby="perfil-ayuda-correo" readonly disabled>
                                    </div>
                                    <small class="perfil-ayuda" id="perfil-ayuda-correo">El correo electrónico es tu identificador único y no se puede modificar directamente.</small>
                                </div>

                                <div>
                                    <label class="perfil-label" for="perfil-input-telefono">Teléfono</label>
                                    <div class="input-group-brutalist">
                                        <span class="input-icon"><i class="fas fa-phone-alt" aria-hidden="true"></i></span>
                                        <input type="tel" id="perfil-input-telefono" name="telefono" class="form-control perfil-input" value="{{ old('telefono', $datosUsuario['telefono']) }}" autocomplete="tel" readonly>
                                    </div>
                                </div>

                                <div class="perfil-campo--completo">
                                    <label class="perfil-label" for="perfil-input-negocio">Nombre del negocio / emprendimiento (opcional)</label>
                                    <div class="input-group-brutalist">
                                        <span class="input-icon"><i class="fas fa-store" aria-hidden="true"></i></span>
                                        <input type="text" id="perfil-input-negocio" name="nombre_negocio" class="form-control perfil-input" value="{{ old('nombre_negocio', $datosUsuario['nombre_negocio']) }}" autocomplete="organization" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="perfil-acciones" id="perfil-acciones-contenedor">
                                <button type="button" class="btn perfil-btn perfil-btn-cancelar" id="perfil-btn-cancelar" hidden>
                                    Cancelar
                                </button>
                                <button type="submit" class="btn perfil-btn perfil-btn-guardar" id="perfil-btn-guardar" hidden>
                                    <i class="fas fa-check perfil-icono" aria-hidden="true"></i> Guardar cambios
                                </button>
                                <button type="button" class="btn perfil-btn perfil-btn-editar" id="perfil-btn-habilitar-edicion">
                                    <i class="fas fa-pencil-alt perfil-icono" aria-hidden="true"></i> Editar información
                                </button>
                            </div>
                        </form>

                    </div>
                </section>

                {{-- Seguridad --}}
                <section class="card perfil-tarjeta perfil-tarjeta--oscura perfil-reveal" id="perfil-tarjeta-seguridad" style="--i:3" aria-labelledby="perfil-subtitulo-seguridad">
                    <div class="perfil-tarjeta-cuerpo">
                        <div class="perfil-tarjeta-cabecera">
                            <h3 class="perfil-subtitulo" id="perfil-subtitulo-seguridad">
                                <i class="fas fa-lock perfil-icono" aria-hidden="true"></i>Seguridad &amp; contraseña
                            </h3>
                        </div>

                        <div class="perfil-seguridad-fila">
                            <div>
                                <p class="perfil-seguridad-titulo">Contraseña de acceso</p>
                                <p class="perfil-seguridad-detalle">Última modificación realizada durante el registro inicial del sistema.</p>
                            </div>
                            <button type="button" class="btn perfil-btn perfil-btn-secundario" id="perfil-btn-cambiar-password">
                                <i class="fas fa-key perfil-icono" aria-hidden="true"></i> Cambiar contraseña
                            </button>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>


    <!-- MODAL PARA RECORTAR FOTO DE PERFIL -->
    <div class="modal fade" id="modalCropper" tabindex="-1" aria-labelledby="modalCropperLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content border-0 shadow-lg" style="background-color: var(--midnight-indigo); color: var(--vanilla-cream);">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalCropperLabel">
                        <i class="fas fa-crop-alt mr-2" style="color: var(--accent-tertiary);"></i> Ajustar Fotografía de Perfil
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" id="btnCerrarCropper" style="opacity: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center p-4">
                    <p class="small text-muted mb-3" style="color: var(--cream-dark) !important;">Mueve y escala la imagen para ajustarla perfectamente al formato cuadrado.</p>
                    <div class="img-container mx-auto" style="max-height: 350px; overflow: hidden; background: #000; border-radius: 6px;">
                        <img id="imageToCrop" src="" alt="Imagen para recortar" style="max-width: 100%; display: block;">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-light fw-bold rounded-pill px-4" data-dismiss="modal" id="btnCancelarRecorte">Cancelar</button>
                    <button type="button" class="btn fw-bold rounded-pill px-4" id="btnRecortarYGuardar" style="background-color: var(--accent-secondary); color: var(--midnight-dark);">
                        <i class="fas fa-check mr-1"></i> Recortar y Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        window.rutasLaravel = {
            actualizarFoto: "{{ route('perfil.actualizar_foto') }}",
            actualizarInformacion: "{{ route('perfil.actualizar_informacion') }}"
        };
    </script>

    {{-- Cropper.js JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    {{-- Archivos de script separados por responsabilidades --}}
    <script src="{{ asset('js/Perfil/perfil-avatar.js') }}"></script>
    <script src="{{ asset('js/Perfil/perfil-edicion.js') }}"></script>
@endpush
