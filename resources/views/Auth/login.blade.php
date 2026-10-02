@extends('layouts.auth')

@section('titulo', 'Iniciar Sesión | Yendo')

@section('contenido')
    <div class="container">
        <div class="row justify-content-center">
            {{-- Tamaño ajustado para mantener las proporciones de la imagen --}}
            <div class="col-11 col-sm-8 col-md-6 col-lg-4">

                <div class="card border-0 shadow-lg login-card">
                    <div class="card-body p-0 text-center">

                        {{-- Logo y Cabecera --}}
                        <div class="text-center">
                            <img src="{{ asset('img/logo.png') }}" alt="Logo Yendo" class="img-fluid" style="max-height: 70px;">
                            <h4 class="login-title">Sistema de logistica</h4>
                        </div>

                        {{-- Formulario --}}
                        <form action="#" method="POST" novalidate>
                            @csrf

                            <div class="form-group text-left mb-3">
                                <label for="correo" class="custom-label">Correo electronico</label>
                                <div class="input-group custom-input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-envelope"></i></span>
                                    </div>
                                    <input type="email" name="correo" id="correo" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group text-left mb-4">
                                <label for="clave" class="custom-label">Contraseña</label>
                                <div class="input-group custom-input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="clave" id="clave" class="form-control" required>
                                </div>
                            </div>

                            {{-- Botones de Acción --}}
                            <button type="submit" class="btn btn-block btn-ingresar mb-3 d-flex justify-content-center align-items-center">
                                Ingresar <i class="fas fa-arrow-right ml-3" style="font-size: 1.1rem;"></i>
                            </button>

                            <a href="#" class="btn btn-block btn-olvidaste d-flex justify-content-center align-items-center">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
