@extends('layouts.auth')

@section('titulo', 'Crear Cuenta | Yendo')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/registro.css') }}">
@endpush

@section('contenido')
    {{-- Incluimos tu archivo de toasts para que el contenedor .toast-container exista en el DOM --}}
    @include('layouts.toast')

    <div class="container registro-minimalista-container">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-10 col-md-9 col-lg-8 text-center">

                <img src="{{ asset('img/logodos.png') }}" alt="Logo Yendo" class="img-fluid mb-4" id="registro-logo-minimalista">

                {{-- PASO 1 --}}
                <div id="paso-1">
                    <h1 class="registro-titulo">Bienvenidos al nuevo sistema yendo</h1>
                    <p class="registro-subtitulo">
                        Un sistema ideal para llevar el control de tu emprendimiento y coordinar la
                        logística de tu encomienda, descubre nuevas funciones registrándote
                    </p>

                    {{-- AGREGADO: novalidate --}}
                    <form id="form-paso-1" class="mt-5" novalidate onsubmit="event.preventDefault(); irAPaso2();">
                        <div class="form-group mb-4 mx-auto" style="max-width: 480px;">
                            <input type="email" id="correo_registro" class="form-control" placeholder="Ingrese su correo electronico" required>
                        </div>

                        <button type="submit" id="btn-continuar-registro" class="btn btn-block mb-3 mx-auto" style="max-width: 480px;">
                            Continuar
                        </button>

                        <p class="registro-nota">
                            Asegúrese de ingresar un correo electrónico válido, para recibir un código antes de continuar
                        </p>

                        <div class="mt-5">
                            <a href="{{ route('login') }}" class="registro-link-login">
                                ¡Te has equivocado! ¿Si tienes una cuenta? Iniciar sesión
                            </a>
                        </div>
                    </form>
                </div>

                {{-- PASO 2 --}}
                <div id="paso-2" style="display: none;">
                    <h1 class="registro-titulo">Crea tu cuenta</h1>
                    <p class="registro-subtitulo mb-1">¡Te hemos enviado un correo electrónico!</p>
                    <p class="registro-subtitulo">Porfavor revisa tú bandeja de entrada e ingresa el código recibido</p>

                    <div class="codigo-container">
                        <input type="text" maxlength="1" class="codigo-input" data-index="1">
                        <input type="text" maxlength="1" class="codigo-input" data-index="2">
                        <input type="text" maxlength="1" class="codigo-input" data-index="3">
                        <input type="text" maxlength="1" class="codigo-input" data-index="4">
                        <input type="text" maxlength="1" class="codigo-input" data-index="5">
                        <input type="text" maxlength="1" class="codigo-input" data-index="6">
                    </div>

                    <div class="mt-4">
                        <a id="btn-reenviar-codigo" class="registro-link-reenviar">
                            Reenviar el código <span id="timer-text"></span>
                        </a>
                    </div>
                </div>

                {{-- PASO 3 --}}
                <div id="paso-3" style="display: none;">
                    <h1 class="registro-titulo">Háblanos de ti</h1>
                    <p class="registro-subtitulo mb-4">Ingresa tú información personal, para completar tú perfil</p>

                    {{-- AGREGADO: novalidate --}}
                    <form id="form-paso-3" method="POST" action="#" novalidate>
                        @csrf
                        <input type="hidden" name="correo_final" id="correo_final">

                        <div class="row text-left mx-auto" style="max-width: 600px;">
                            <div class="col-md-6 form-group mb-4">
                                <label class="label-blanco">Nombres</label>
                                <input type="text" name="nombres" class="input-minimalista-sm" placeholder="Ingrese sus dos nombres" required>
                            </div>
                            <div class="col-md-6 form-group mb-4">
                                <label class="label-blanco">Apellidos</label>
                                <input type="text" name="apellidos" class="input-minimalista-sm" placeholder="Ingrese sus dos apellidos" required>
                            </div>

                            <div class="col-md-6 form-group mb-4">
                                <label class="label-blanco">Crea una contraseña</label>
                                <input type="password" name="password" class="input-minimalista-sm" placeholder="Ingrese una contraseña" required>
                            </div>
                            <div class="col-md-6 form-group mb-4">
                                <label class="label-blanco">Repita su contraseña</label>
                                <input type="password" name="password_confirmation" class="input-minimalista-sm" placeholder="Repita su contraseña" required>
                            </div>
                        </div>

                        <button type="submit" id="btn-finalizar-registro" class="btn btn-block mt-4 mx-auto" style="max-width: 400px; background-color: transparent; color: white; border: 3px solid white; border-radius: 6px; height: 55px; font-size: 1.15rem; font-weight: 900;">
                            Continuar
                        </button>
                    </form>
                </div>

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

@endpush
