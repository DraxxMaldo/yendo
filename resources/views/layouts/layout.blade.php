<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo', 'Yendo - Sistema de Encomiendas')</title>

    {{-- Font Awesome (iconografía de AdminLTE) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    {{-- AdminLTE CSS vía CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    {{-- Hoja de estilo propia del proyecto, SIEMPRE después del framework para poder sobrescribirlo[cite: 4] --}}
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">

    @stack('estilos')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    {{-- 1. BARRA SUPERIOR --}}
    <nav class="main-header navbar navbar-expand">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fas fa-user-circle mr-1"></i>
                    {{ Auth::user()->perfil->nombres ?? 'Usuario' }}
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow border-0 rounded-3">
                    <form ">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger fw-bold">
                            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    {{-- 2. MENÚ LATERAL --}}
    <aside class="main-sidebar elevation-4">

        <a href="{{ route('home') }}" class="brand-link text-decoration-none text-center">
            {{-- Se inserta la imagen usando asset() --}}
            <img src="{{ asset('img/logodos.png') }}" alt="Logo Yendo Logística" class="img-fluid" style="max-height: 40px; width: auto;">
        </a>

        <div class="sidebar">
            <nav class="mt-4">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('usuarios.index') }}" class="nav-link {{ request()->routeIs('usuarios.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Usuarios</p>
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>

    {{-- 3. CONTENIDO CENTRAL --}}
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0" style="color: var(--midnight-indigo);">@yield('titulo', 'Panel')</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @yield('contenido')
            </div>
        </section>
    </div>

    {{-- 4. PIE DE PÁGINA --}}
    <footer class="main-footer text-sm">
        <div class="float-right d-none d-sm-block">
            <b>Versión</b> 1.0
        </div>
        <strong>Copyright &copy; 2026 Yendo - Sistema de Encomiendas.</strong> Todos los derechos reservados.
    </footer>

</div>

{{-- TOASTS --}}
@include('layouts.toast')

{{-- SCRIPTS --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="{{ asset('js/toast.js') }}"></script>

@stack('scripts')
</body>
</html>
