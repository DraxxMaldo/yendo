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
    <link rel="stylesheet" href="{{ asset('css/barra-superior.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu-lateral.css') }}">

    @stack('estilos')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    {{-- 1. BARRA SUPERIOR --}}
    {{-- Conserva .main-header .navbar .navbar-expand (AdminLTE las necesita). Estilos en public/css/barra-superior.css --}}
    <nav class="main-header navbar navbar-expand" id="main-header" aria-label="Barra superior">

        {{-- Izquierda: botón del sidebar --}}
        <ul class="navbar-nav" id="topbar-left-nav">
            <li class="nav-item" id="sidebar-toggle-item">
                <a class="nav-link" id="sidebar-toggle-btn" data-widget="pushmenu" href="#" role="button"
                   aria-label="Mostrar u ocultar menú lateral" title="Menú">
                    <i class="fas fa-bars" aria-hidden="true"></i>
                </a>
            </li>
        </ul>

        {{-- Derecha: usuario --}}
        <ul class="navbar-nav ml-auto" id="topbar-right-nav">
            <li class="nav-item dropdown" id="user-dropdown-item">

                {{-- Botón tipo píldora --}}
                <a class="nav-link dropdown-toggle" id="user-dropdown-toggle"
                   data-toggle="dropdown" href="#" role="button"
                   aria-haspopup="true" aria-expanded="false" aria-label="Menú de usuario">

                <span id="user-avatar-box" aria-hidden="true">
                    <i class="fas fa-user-circle"></i>
                </span>

                    <span id="user-text-block">
                    <span id="user-name-label">{{ Auth::user()->perfil->nombres ?? 'Usuario' }}</span>
                    <small id="user-role-label">{{ Auth::user()->rol->nombre_rol ?? 'Administrador' }}</small>
                </span>

                    <i class="fas fa-chevron-down" id="user-dropdown-chevron" aria-hidden="true"></i>
                </a>

                {{-- Menú desplegable --}}
                <div class="dropdown-menu dropdown-menu-right" id="user-dropdown-menu">

                    {{-- Cabecera informativa --}}
                    <div id="user-menu-header">
                    <span id="user-menu-avatar" aria-hidden="true">
                        <i class="fas fa-user-circle"></i>
                    </span>
                        <div id="user-menu-text">
                            <span id="user-menu-name">{{ Auth::user()->perfil->nombres ?? 'Usuario' }}</span>
                            <small id="user-menu-role">{{ Auth::user()->rol->nombre_rol ?? 'Administrador' }}</small>
                        </div>
                    </div>

                    {{-- Perfil --}}
                    <a href="{{ route('perfil.index') }}" class="dropdown-item user-menu-item" id="user-menu-profile-link">
                        <i class="fas fa-id-badge" aria-hidden="true"></i>
                        <span>Perfil</span>
                    </a>

                    {{-- Cerrar sesión --}}
                    <form action="{{ route('logout') }}" method="POST" id="user-menu-logout-form">
                        @csrf
                        <button type="submit" class="dropdown-item user-menu-item" id="user-menu-logout-btn">
                            <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                            <span>Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    {{-- 2. MENÚ LATERAL --}}
    {{-- Conserva .main-sidebar y las clases AdminLTE (nav-pills nav-sidebar, data-widget="treeview").
         Se quitó "elevation-4" porque AdminLTE lo fuerza con !important. Estilos en public/css/menu-lateral.css --}}
    <aside class="main-sidebar" id="main-sidebar" aria-label="Menú lateral">

        {{-- Marca --}}
        <a href="{{ route('home') }}" class="brand-link text-decoration-none" id="sidebar-brand-link" aria-label="Ir al inicio de Yendo Logística">
            <img src="{{ asset('img/logodos.png') }}" alt="Logo Yendo Logística" class="img-fluid" id="sidebar-brand-logo">
        </a>

        <div class="sidebar" id="sidebar-scroll">
            <nav id="sidebar-nav" aria-label="Navegación principal">
                <ul class="nav nav-pills nav-sidebar flex-column" id="sidebar-menu" data-widget="treeview" role="menu" data-accordion="false">

                    <li class="nav-header" id="sidebar-nav-header">Navegación</li>

                    {{-- Destinos (vista pública de sucursales) --}}
                    <li class="nav-item" id="sidebar-item-destinos">
                        <a href="{{ route('destinos') }}" id="sidebar-link-destinos" title="Destinos"
                           class="nav-link {{ request()->routeIs('destinos') ? 'active' : '' }}"
                           aria-current="{{ request()->routeIs('destinos') ? 'page' : 'false' }}">
                            <i class="nav-icon fas fa-map-marked-alt" id="sidebar-icon-destinos" aria-hidden="true"></i>
                            <p id="sidebar-label-destinos">Destinos</p>
                        </a>
                    </li>


                    @if(Auth::check() && Auth::user()->id_rol == 1)
                    {{-- Dashboard --}}
                    <li class="nav-item" id="sidebar-item-dashboard">
                        <a href="{{ route('home') }}" id="sidebar-link-dashboard" title="Dashboard"
                           class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                           aria-current="{{ request()->routeIs('home') ? 'page' : 'false' }}">
                            <i class="nav-icon fas fa-tachometer-alt" id="sidebar-icon-dashboard" aria-hidden="true"></i>
                            <p id="sidebar-label-dashboard">Dashboard</p>
                        </a>
                    </li>
                    @endif

                    @if(Auth::check() && Auth::user()->id_rol == 1)
                    {{-- Usuarios (se mantiene activo también en create/edit/show) --}}
                    <li class="nav-item" id="sidebar-item-usuarios">
                        <a href="{{ route('usuarios.index') }}" id="sidebar-link-usuarios" title="Usuarios"
                           class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}"
                           aria-current="{{ request()->routeIs('usuarios.*') ? 'page' : 'false' }}">
                            <i class="nav-icon fas fa-user" id="sidebar-icon-usuarios" aria-hidden="true"></i>
                            <p id="sidebar-label-usuarios">Usuarios</p>
                        </a>
                    </li>
                    @endif

                    @if(Auth::check() && Auth::user()->id_rol == 1)
                    {{-- Auditoría --}}
                    <li class="nav-item" id="sidebar-item-auditoria">
                        <a href="{{ route('bitacora') }}" id="sidebar-link-auditoria" title="Auditoría"
                           class="nav-link {{ request()->routeIs('bitacora') ? 'active' : '' }}"
                           aria-current="{{ request()->routeIs('bitacora') ? 'page' : 'false' }}">
                            <i class="nav-icon fas fa-shield-alt" id="sidebar-icon-auditoria" aria-hidden="true"></i>
                            <p id="sidebar-label-auditoria">Auditoría</p>
                        </a>
                    </li>
                    @endif

                    @if(Auth::check() && in_array(Auth::user()->id_rol, [1, 2]))                    {{-- Establecimientos (activo también en create/edit/show) --}}
                    <li class="nav-item" id="sidebar-item-establecimientos">
                        <a href="{{ route('establecimientos.index') }}" id="sidebar-link-establecimientos" title="Establecimientos"
                           class="nav-link {{ request()->routeIs('establecimientos.*') ? 'active' : '' }}"
                           aria-current="{{ request()->routeIs('establecimientos.*') ? 'page' : 'false' }}">
                            <i class="nav-icon fas fa-building" id="sidebar-icon-establecimientos" aria-hidden="true"></i>
                            <p id="sidebar-label-establecimientos">Establecimientos</p>
                        </a>
                    </li>
                    @endif

                    @if(Auth::check() && Auth::user()->id_rol == 1)
                        <li class="nav-item" id="sidebar-item-solicitudes">
                            <a href="{{ route('admin.solicitudes_afiliacion') }}" id="sidebar-link-solicitudes" title="Solicitudes de Afiliación"
                               class="nav-link {{ request()->routeIs('admin.solicitudes_afiliacion') ? 'active' : '' }}"
                               aria-current="{{ request()->routeIs('admin.solicitudes_afiliacion') ? 'page' : 'false' }}">
                                <i class="nav-icon fas fa-user-check" id="sidebar-icon-solicitudes" aria-hidden="true"></i>
                                <p id="sidebar-label-solicitudes">Solicitudes Afiliación</p>
                            </a>
                        </li>
                    @endif

                    {{-- Gestión de Empleados (Visible para Admin ID 1 y Afiliado ID 2) --}}
                    @if(Auth::check() && Auth::user()->id_rol == 2)
                        <li class="nav-item" id="sidebar-item-empleados">
                            <a href="{{ route('empleados.index') }}" id="sidebar-link-empleados" title="Gestionar Empleados"
                               class="nav-link {{ request()->routeIs('empleados.*') ? 'active' : '' }}"
                               aria-current="{{ request()->routeIs('empleados.*') ? 'page' : 'false' }}">
                                <i class="nav-icon fas fa-user-tie" id="sidebar-icon-empleados" aria-hidden="true"></i>
                                <p id="sidebar-label-empleados">Gestion Empleados</p>
                            </a>
                        </li>
                    @endif

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
