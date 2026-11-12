@extends('layouts.layout')

@section('titulo', 'Cambiar Contraseña | Yendo')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfil-seguridad.css') }}">
@endpush

@section('contenido')
    <!-- SISTEMA GLOBAL DE TOASTS -->
    @include('layouts.toast')

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-custom border-0 shadow-lg p-4">

                    <!-- ENCABEZADO -->
                    <div class="text-center mb-4">
                        <h2 class="fw-bold" style="color: var(--midnight-indigo);">Seguridad de la Cuenta</h2>
                        <p class="text-muted">Proceso de verificación en dos pasos para restablecer tu contraseña.</p>
                    </div>

                    @php
                        $codigoEnviado = session('codigo_enviado', false);
                        $verificado = session('pwd_verificado', false);
                        $tipoCambio = session('tipo_cambio_pwd', 'manual');
                    @endphp

                        <!-- PASO 1: SELECCIONAR MÉTODO Y ENVIAR CÓDIGO -->
                    <div id="paso-1" class="text-center py-4" style="{{ ($codigoEnviado || $verificado) ? 'display: none;' : '' }}">
                        <i class="fas fa-key fa-3x mb-3" style="color: var(--midnight-indigo);"></i>
                        <h4>¿Cómo deseas cambiar tu contraseña?</h4>
                        <p class="text-muted mb-4">Selecciona una opción. En ambos casos enviaremos un código de verificación a tu correo.</p>

                        <form action="{{ route('perfil.seguridad.enviar_codigo') }}" method="POST">
                            @csrf
                            <div class="d-flex justify-content-center gap-3 mb-4">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="tipoManual" name="tipo_cambio" value="manual" class="custom-control-input" {{ $tipoCambio === 'manual' ? 'checked' : '' }}>
                                    <label class="custom-control-label fw-bold" for="tipoManual" style="color: var(--midnight-indigo);">Crear mi propia contraseña</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="tipoAleatorio" name="tipo_cambio" value="aleatorio" class="custom-control-input" {{ $tipoCambio === 'aleatorio' ? 'checked' : '' }}>
                                    <label class="custom-control-label fw-bold" for="tipoAleatorio" style="color: var(--midnight-indigo);">Generar contraseña aleatoria</label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center align-items-center gap-3">
                                <button type="submit" class="btn btn-brand fw-bold rounded-pill px-4 py-2" onclick="this.innerHTML='<i class=\'fas fa-spinner fa-spin mr-2\'></i> Enviando...';">
                                    <i class="fas fa-paper-plane mr-2"></i> Continuar y Enviar Código
                                </button>
                                <a href="{{ route('perfil.index') }}" class="btn btn-outline-secondary fw-bold rounded-pill px-4 py-2">
                                    Cancelar
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- PASO 2: INGRESAR CÓDIGO -->
                    <div id="paso-2" class="text-center py-4" style="{{ ($codigoEnviado && !$verificado) ? '' : 'display: none;' }}">
                        <i class="fas fa-shield-alt fa-3x mb-3 text-warning"></i>
                        <h4>Paso 2: Ingresa el código de 6 dígitos</h4>
                        <p class="text-muted mb-2">Revisa tu bandeja de entrada.</p>
                        <p class="small text-danger mb-3">El código expirará en <span id="contador" class="fw-bold">60</span> segundos.</p>

                        <form action="{{ route('perfil.seguridad.verificar_codigo') }}" method="POST" novalidate>
                            @csrf
                            <div class="d-flex justify-content-center mb-4">
                                <input type="text" name="codigo" class="form-control text-center fw-bold fs-3" maxlength="6" placeholder="------" style="letter-spacing: 10px; max-width: 250px; font-size: 1.5rem;" required>
                            </div>

                            <div class="mb-3 d-flex justify-content-center align-items-center gap-2">
                                <button type="submit" class="btn btn-brand fw-bold rounded-pill px-4 py-2" onclick="this.innerHTML='<i class=\'fas fa-spinner fa-spin mr-2\'></i> Verificando...';">
                                    Verificar Código
                                </button>
                                <a href="{{ route('perfil.index') }}" class="btn btn-outline-secondary fw-bold rounded-pill px-4 py-2">
                                    Cancelar
                                </a>
                            </div>
                        </form>

                        <form id="formReenviar" action="{{ route('perfil.seguridad.enviar_codigo') }}" method="POST" class="mt-3" novalidate>
                            @csrf
                            <input type="hidden" name="tipo_cambio" value="{{ $tipoCambio }}">
                            <button type="submit" id="btnReenviar" class="btn btn-link text-primary small fw-bold">
                                ¿No llegó? Reenviar código
                            </button>
                        </form>
                    </div>

                    <!-- PASO 3: CONFIGURAR NUEVA CONTRASEÑA O CONFIRMAR ALEATORIA -->
                    <div id="paso-3" class="text-center py-4" style="{{ $verificado ? '' : 'display: none;' }}">
                        <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                        <h4>Paso 3: Identidad Verificada</h4>

                        <form action="{{ route('perfil.seguridad.actualizar_final') }}" method="POST" novalidate>
                            @csrf
                            @if($tipoCambio === 'manual')
                                <p class="text-muted mb-3">Ingresa y confirma tu nueva contraseña segura.</p>

                                <div class="form-group text-left mx-auto" style="max-width: 350px;">
                                    <label class="fw-bold" style="color: var(--midnight-indigo);">Nueva contraseña</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="password" class="form-control" required minlength="8">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group text-left mx-auto mb-4" style="max-width: 350px;">
                                    <label class="fw-bold" style="color: var(--midnight-indigo);">Confirmar contraseña</label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required minlength="8">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password_confirmation">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted mb-4">Has confirmado que eres tú. Al hacer clic en el botón inferior, generaremos una contraseña aleatoria de 8 caracteres y te la enviaremos por correo.</p>
                            @endif

                            <div class="d-flex justify-content-center align-items-center gap-3">
                                <button type="submit" class="btn btn-success fw-bold rounded-pill px-4 py-2" onclick="this.innerHTML='<i class=\'fas fa-spinner fa-spin mr-2\'></i> Procesando...';">
                                    <i class="fas fa-key mr-2"></i> Confirmar y Cambiar Contraseña
                                </button>
                                <a href="{{ route('perfil.index') }}" class="btn btn-outline-secondary fw-bold rounded-pill px-4 py-2">
                                    Cancelar
                                </a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/Perfil/perfil-seguridad.js') }}"></script>
@endpush
