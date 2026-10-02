<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Permite que la vista hija sobrescriba el título, de lo contrario usa el valor por defecto -->
    <title>@yield('titulo', 'Sistema de Encomienda')</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Hoja de estilo corporativa referenciada correctamente desde public/css -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Pila para que cada vista inyecte CSS extra si lo necesita -->
    @stack('estilos')
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<!-- Contenedor principal donde se inyectará el contenido de las vistas (ej. el login) -->
<main class="flex-grow-1 d-flex align-items-center justify-content-center">
    @yield('contenido')
</main>

<!-- Pie de página genérico para las vistas públicas -->
<footer class="text-center text-muted small py-3 mt-auto">
    <strong>Sistema de Encomienda</strong> &copy; {{ date('Y') }}
</footer>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Pila para acumular JS extra por página -->
@stack('scripts')
</body>
</html>
