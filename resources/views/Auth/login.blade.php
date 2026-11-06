@extends('layouts.auth')

@section('titulo', 'Iniciar Sesión | Yendo')

@section('contenido')
    <div class="container" id="login-wrapper">
        <div class="row justify-content-center" id="login-row">
            {{-- Tamaño ajustado para mantener las proporciones de la imagen --}}
            <div class="col-11 col-sm-8 col-md-6 col-lg-4" id="login-column">

                {{-- Se quitaron "border-0 shadow-lg": Bootstrap los fuerza con !important y pisan el diseño --}}
                <main class="card login-card" id="login-card">


                    <div class="card-body p-0 text-center" id="login-card-body">

                        {{-- Logo y cabecera --}}
                        <header class="text-center" id="login-header">
                            <img src="{{ asset('img/logo.png') }}" alt="Logo Yendo" class="img-fluid" id="login-logo" width="auto" height="70" style="max-height: 70px;">
                            <h1 class="login-title" id="login-title">Sistema de logística</h1>
                        </header>

                        {{-- Formulario --}}
                        <form action="{{ route('login.post') }}" method="POST" id="login-form" novalidate>
                            @csrf

                            <div class="form-group text-left mb-3" id="login-group-correo">
                                <label for="correo" class="custom-label" id="login-label-correo">Correo electrónico</label>
                                <div class="input-group custom-input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-envelope" aria-hidden="true"></i></span>
                                    </div>
                                    {{-- Conserva value="{{ old('correo') }}" --}}
                                    <input type="email" name="correo" id="correo" class="form-control"
                                           value="{{ old('correo') }}" autocomplete="email" autofocus required>
                                </div>
                            </div>

                            <div class="form-group text-left mb-4" id="login-group-clave">
                                <label for="clave" class="custom-label" id="login-label-clave">Contraseña</label>
                                <div class="input-group custom-input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock" aria-hidden="true"></i></span>
                                    </div>
                                    <input type="password" name="clave" id="clave" class="form-control"
                                           autocomplete="current-password" required>
                                    {{-- #btnToggleClave e #iconoClave se mantienen para tu JS de mostrar/ocultar.
                                         Ahora es un <button> real: accesible por teclado (Tab / Enter / Espacio) --}}
                                    <div class="input-group-append">
                                        <button type="button" class="input-group-text" id="btnToggleClave"
                                                aria-label="Mostrar u ocultar contraseña" title="Mostrar u ocultar contraseña">
                                            <i class="fas fa-eye" id="iconoClave" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Botones de acción --}}
                            <button type="submit" class="btn btn-ingresar mb-3" id="login-submit-btn">
                                Ingresar <i class="fas fa-arrow-right" id="login-submit-icon" aria-hidden="true"></i>
                            </button>

                            <a href="{{ route('password.request') }}" class="btn btn-olvidaste" id="login-forgot-btn">
                                ¿Olvidaste tu contraseña?
                            </a>

                            <div id="registro-container">
                                <span id="registro-mensaje">¿Aún no tienes una cuenta?</span>
                                <a href="{{ route('registro') }}" id="registro-enlace">Regístrate en Yendo</a>
                            </div>

                        </form>
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection
