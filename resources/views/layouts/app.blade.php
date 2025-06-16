<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- App Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Bootstrap (opcional si no está en app.css) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert2 (siempre disponible para las vistas) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts personalizados por vistas -->
    @yield('head')
</head>
<body>
    {{-- Desactiva el logout automático al cerrar la pestaña (opcional, peligroso) --}}
    {{-- <script>
        window.addEventListener('beforeunload', function () {
            navigator.sendBeacon('/logout');
        });
    </script> --}}

    <main class="py-4">
        @yield('content')
    </main>

    <!-- App Script -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JS de cada vista -->
    @yield('scripts')
</body>
</html>
