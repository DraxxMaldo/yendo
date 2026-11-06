@extends('layouts.auth')

@section('titulo', 'Crear Cuenta | Yendo')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/registro.css') }}">
@endpush

@section('contenido')
    {{-- Incluimos tu archivo de toasts para que el contenedor .toast-container exista en el DOM --}}
    @include('layouts.toast')

    <div class="container registro-minimalista-container" id="registro-wrapper">
        <div class="row justify-content-center" id="registro-row">
            <div class="col-11 col-sm-10 col-md-9 col-lg-8 text-center" id="registro-column">

                <main id="registro-card">
                    <span id="registro-card-tag" aria-hidden="true">Nueva cuenta</span>

                    <img src="{{ asset('img/logo.png') }}" alt="Logo Yendo" class="img-fluid" id="registro-logo-minimalista">

                    {{-- Indicador de pasos (se sincroniza solo con el paso visible, ver script al final) --}}
                    <ol id="registro-stepper" data-step="1" aria-label="Progreso del registro">
                        <li class="registro-step" id="registro-step-1" aria-current="step">
                            <span class="registro-step-num">1</span>
                            <span class="registro-step-label">Correo</span>
                        </li>
                        <li class="registro-step" id="registro-step-2">
                            <span class="registro-step-num">2</span>
                            <span class="registro-step-label">Código</span>
                        </li>
                        <li class="registro-step" id="registro-step-3">
                            <span class="registro-step-num">3</span>
                            <span class="registro-step-label">Perfil</span>
                        </li>
                    </ol>

                    {{-- PASO 1 --}}
                    <div id="paso-1">
                        <h1 class="registro-titulo" id="registro-titulo-paso-1">Bienvenidos al nuevo sistema Yendo</h1>
                        <p class="registro-subtitulo" id="registro-subtitulo-paso-1">
                            Un sistema ideal para llevar el control de tu emprendimiento y coordinar la
                            logística de tu encomienda, descubre nuevas funciones registrándote
                        </p>

                        {{-- novalidate + onsubmit se mantienen tal cual: irAPaso2() es parte de tu flujo --}}
                        <form id="form-paso-1" class="mt-5" novalidate onsubmit="event.preventDefault(); irAPaso2();">
                            <div class="form-group mb-4 registro-campo" id="registro-group-correo">
                                <label for="correo_registro" class="registro-label" id="registro-label-correo">Correo electrónico</label>
                                <input type="email" id="correo_registro" class="form-control registro-input"
                                       placeholder="Ingrese su correo electrónico" autocomplete="email" required>
                            </div>

                            <button type="submit" id="btn-continuar-registro" class="btn registro-btn mb-3">
                                Continuar <i class="fas fa-arrow-right ml-3" aria-hidden="true"></i>
                            </button>

                            <p class="registro-nota" id="registro-nota-correo">
                                <i class="fas fa-info-circle" aria-hidden="true"></i>
                                Asegúrese de ingresar un correo electrónico válido, para recibir un código antes de continuar
                            </p>

                            <div id="registro-login-wrap">
                                <a href="{{ route('login') }}" class="registro-link-login" id="registro-link-login">
                                    ¿Ya tienes una cuenta? Inicia sesión
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- PASO 2 (el JS controla su visibilidad con style.display: se deja el inline) --}}
                    <div id="paso-2" style="display: none;">
                        <h1 class="registro-titulo" id="registro-titulo-paso-2">Crea tu cuenta</h1>
                        <p class="registro-subtitulo mb-1">¡Te hemos enviado un correo electrónico!</p>
                        <p class="registro-subtitulo">Por favor, revisa tu bandeja de entrada e ingresa el código recibido</p>

                        <div class="codigo-container" id="registro-codigo-container" role="group" aria-label="Código de verificación de 6 dígitos">
                            <input type="text" maxlength="1" class="codigo-input" data-index="1" aria-label="Dígito 1 de 6" autocomplete="off">
                            <input type="text" maxlength="1" class="codigo-input" data-index="2" aria-label="Dígito 2 de 6" autocomplete="off">
                            <input type="text" maxlength="1" class="codigo-input" data-index="3" aria-label="Dígito 3 de 6" autocomplete="off">
                            <input type="text" maxlength="1" class="codigo-input" data-index="4" aria-label="Dígito 4 de 6" autocomplete="off">
                            <input type="text" maxlength="1" class="codigo-input" data-index="5" aria-label="Dígito 5 de 6" autocomplete="off">
                            <input type="text" maxlength="1" class="codigo-input" data-index="6" aria-label="Dígito 6 de 6" autocomplete="off">
                        </div>

                        <div class="mt-4">
                            <a id="btn-reenviar-codigo" class="registro-link-reenviar" role="button" tabindex="0">
                                Reenviar el código <span id="timer-text"></span>
                            </a>
                        </div>
                    </div>

                    {{-- PASO 3 --}}
                    <div id="paso-3" style="display: none;">
                        <h1 class="registro-titulo" id="registro-titulo-paso-3">Háblanos de ti</h1>
                        <p class="registro-subtitulo mb-4">Ingresa tu información personal para completar tu perfil</p>

                        <form id="form-paso-3" method="POST" action="#" novalidate>
                            @csrf
                            <input type="hidden" name="correo_final" id="correo_final">

                            <div class="row text-left mx-auto" id="registro-form-campos">
                                <div class="col-md-6 form-group mb-4">
                                    <label for="registro-input-nombres" class="registro-label">Nombres</label>
                                    <input type="text" name="nombres" id="registro-input-nombres" class="input-minimalista-sm"
                                           placeholder="Ingrese sus dos nombres" autocomplete="given-name" required>
                                </div>
                                <div class="col-md-6 form-group mb-4">
                                    <label for="registro-input-apellidos" class="registro-label">Apellidos</label>
                                    <input type="text" name="apellidos" id="registro-input-apellidos" class="input-minimalista-sm"
                                           placeholder="Ingrese sus dos apellidos" autocomplete="family-name" required>
                                </div>

                                <div class="col-md-6 form-group mb-4">
                                    <label for="registro-input-password" class="registro-label">Crea una contraseña</label>
                                    <input type="password" name="password" id="registro-input-password" class="input-minimalista-sm"
                                           placeholder="Ingrese una contraseña" autocomplete="new-password" required>
                                </div>
                                <div class="col-md-6 form-group mb-4">
                                    <label for="registro-input-password-confirm" class="registro-label">Repita su contraseña</label>
                                    <input type="password" name="password_confirmation" id="registro-input-password-confirm" class="input-minimalista-sm"
                                           placeholder="Repita su contraseña" autocomplete="new-password" required>
                                </div>
                            </div>

                            <button type="submit" id="btn-finalizar-registro" class="btn registro-btn mt-4">
                                Continuar <i class="fas fa-arrow-right ml-3" aria-hidden="true"></i>
                            </button>
                        </form>
                    </div>

                </main>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Scripts --}}
    <script>
        window.rutasLaravel = {
            enviarCodigo: "{{ route('registro.enviar_codigo') }}",
            verificarCodigo: "{{ route('registro.verificar_codigo') }}",
            finalizar: "{{ route('registro.finalizar') }}",
            login: "{{ route('login') }}"
        };
    </script>
    <script src="{{ asset('js/Auth/Flujo-SPA-registro.js') }}"></script>

    {{-- Solo visual y opcional (puedes borrarlo sin afectar el flujo):
         1) sincroniza el indicador de pasos con el paso que muestre tu JS
         2) permite Enter/Espacio en "Reenviar el código" (es un <a> sin href) --}}
    <script>
        (function () {
            var stepper = document.getElementById('registro-stepper');
            var pasos = [1, 2, 3].map(function (n) { return document.getElementById('paso-' + n); });

            function sincronizar() {
                var activo = 1;
                pasos.forEach(function (p, i) {
                    if (p && window.getComputedStyle(p).display !== 'none') activo = i + 1;
                });
                if (stepper) stepper.dataset.step = activo;
                [1, 2, 3].forEach(function (n) {
                    var li = document.getElementById('registro-step-' + n);
                    if (!li) return;
                    if (n === activo) li.setAttribute('aria-current', 'step');
                    else li.removeAttribute('aria-current');
                });
            }

            if (stepper && 'MutationObserver' in window) {
                var obs = new MutationObserver(sincronizar);
                pasos.forEach(function (p) {
                    if (p) obs.observe(p, { attributes: true, attributeFilter: ['style', 'class', 'hidden'] });
                });
                sincronizar();
            }

            var reenviar = document.getElementById('btn-reenviar-codigo');
            if (reenviar) {
                reenviar.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        reenviar.click();
                    }
                });
            }
        })();
    </script>
@endpush
