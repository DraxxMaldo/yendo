<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo', 'Yendo - Sistema de Encomiendas')</title>

    {{-- Font Awesome (iconografía de AdminLTE) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    {{-- AdminLTE CSS vía CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    {{-- Hoja de estilo propia del proyecto, SIEMPRE después del framework para poder sobrescribirlo --}}
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

        <ul class="navbar-nav ml-auto p-2">
            <li class="nav-item dropdown">
                {{-- BOTÓN TIPO PÍLDORA (Estilos movidos a la clase .btn-usuario-pildora) --}}
                <a class="nav-link dropdown-toggle d-flex align-items-center btn-usuario-pildora"
                   data-toggle="dropdown" href="#">

                    <i class="fas fa-user-circle" style="color: var(--midnight-indigo); font-size: 2.2rem;"></i>

                    <div class="d-flex flex-column text-left">
                        <span class="fw-bold" style="color: var(--midnight-indigo); line-height: 1.1; font-size: 1.05rem;">
                            {{ Auth::user()->perfil->nombres ?? 'Usuario' }}
                        </span>
                        <small style="color: rgba(40, 43, 74, 0.7); line-height: 1; font-size: 0.8rem;">
                            {{ Auth::user()->rol->nombre_rol ?? 'Administrador' }}
                        </small>
                    </div>

                    <i class="fas fa-chevron-circle-down ml-2" style="color: var(--midnight-indigo); font-size: 1.1rem; opacity: 0.8;"></i>
                </a>

                {{-- MENÚ DESPLEGABLE --}}
                <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 mt-2"
                     style="min-width: 260px; border-radius: 15px;">

                    {{-- 1. Cabecera del Menú (Informativa) --}}
                    <div class="dropdown-item d-flex align-items-center py-3" style="pointer-events: none; border-radius: 15px 15px 0 0; background-color: rgba(40, 43, 74, 0.03);">
                        <i class="fas fa-user-circle fa-2x mr-3" style="color: var(--midnight-indigo);"></i>
                        <div class="d-flex flex-column">
                            <span class="fw-bold" style="color: var(--midnight-indigo); font-size: 1.1rem;">
                                {{ Auth::user()->perfil->nombres ?? 'Usuario' }}
                            </span>
                            <small style="color: rgba(40, 43, 74, 0.7);">
                                {{ Auth::user()->rol->nombre_rol ?? 'Administrador' }}
                            </small>
                        </div>
                    </div>

                    <div class="dropdown-divider my-0"></div>

                    {{-- 2. Opción: Perfil --}}
                    <a href="#" class="dropdown-item py-3 fw-bold d-flex align-items-center" style="color: var(--midnight-indigo);">
                        <i class="fas fa-id-badge mr-3 text-center" style="color: var(--midnight-indigo); font-size: 1.25rem; width: 25px;"></i>
                        <span style="font-size: 1.1rem;">Perfil</span>
                    </a>

                    <div class="dropdown-divider my-0"></div>

                    {{-- 3. Opción: Cerrar Sesión --}}
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item py-3 fw-bold d-flex align-items-center"
                                style="cursor: pointer; border-radius: 0 0 15px 15px; color: var(--midnight-indigo);">
                            <i class="fas fa-sign-out-alt mr-3 text-center" style="color: var(--midnight-indigo); font-size: 1.25rem; width: 25px;"></i>
                            <span style="font-size: 1.1rem;">Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    {{-- 2. MENÚ LATERAL --}}
    <aside class="main-sidebar elevation-4">
        <a href="{{ route('home') }}" class="brand-link text-decoration-none text-center">
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

                    <li class="nav-item">
                        <a href="{{ route('bitacora') }}" class="nav-link {{ request()->routeIs('bitacora') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shield-alt"></i>
                            <p>Auditoría</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('establecimientos.index') }}" class="nav-link {{ request()->routeIs('establecimientos.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-building"></i>
                            <p>Establecimientos</p>
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
