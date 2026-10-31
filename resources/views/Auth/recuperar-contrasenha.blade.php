@extends('layouts.auth')

@section('titulo', 'Recuperar Contraseña | Yendo')

@push('scripts')
    {{-- Llamamos al script que maneja el spinner --}}
    <script src="{{ asset('js/Auth/recuperar.js') }}?v=1.0"></script>
@endpush

@section('contenido')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-8 col-md-6 col-lg-4">

                <div class="card border-0 shadow-lg login-card">
                    <div class="card-body p-0 text-center">

                        <div class="text-center">

                            <h4 class="login-title">Recuperar contraseña</h4>
                            <img src="{{ asset('img/OlvidasteContrasenhaImagen.jpg') }}" alt="Logo Yendo" class="img-fluid" style="max-height: 100px;">
                            <p class="text-muted small px-4 mb-4">Ingresa tu correo electrónico y te enviaremos las instrucciones para restablecer tu acceso.</p>
                        </div>



                        {{-- Formulario con el ID que detecta nuestro JS --}}
                        <form id="formRecuperar" action="{{ route('password.email') }}" method="POST" novalidate>
                            @csrf

                            <div class="form-group text-left mb-4">
                                <label for="correo" class="custom-label">Correo electrónico</label>
                                <div class="input-group custom-input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-envelope"></i></span>
                                    </div>
                                    <input type="email" name="correo" id="correo" class="form-control" placeholder="usuario@correo.com" required>
                                </div>
                            </div>

                            <button type="submit" id="btnRecuperar" class="btn btn-block btn-ingresar mb-3 d-flex justify-content-center align-items-center">
                                <span id="textoBtn">Enviar instrucciones</span>
                                <i id="iconoBtn" class="fas fa-paper-plane ml-2"></i>
                                <span id="spinnerBtn" class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true" style="display: none;"></span>
                            </button>

                            <a href="{{ route('login') }}" class="btn btn-block btn-olvidaste d-flex justify-content-center align-items-center">
                                <i class="fas fa-arrow-left mr-2"></i> Volver al Login
                            </a>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
