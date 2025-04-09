<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/Favicon2.png') }}" type="image/x-icon">
    <title>@yield('title', 'Panel de Administración')</title>

    <!-- Fuentes de Google: Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- OverlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/dist/css/adminlte.min.css') }}">
    <!-- Estilos personalizados -->
    @yield('styles')
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9fafb;
            transition: all 0.3s ease;
        }
        .main-header.navbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 10px 15px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        .main-sidebar {
            background: #f8fafc;
            border-right: 1px solid #e5e7eb;
        }
        .content-wrapper {
            background: #f9fafb;
            padding: 15px;
        }
        .main-footer {
            background: #f1f5f9;
            color: #475569;
            border-top: 1px solid #e5e7eb;
            padding: 10px;
            font-size: 0.9rem;
            text-align: center;
        }
        .brand-link {
            background: #ffffff;
            color: #1e293b;
            border-bottom: 1px solid #e5e7eb;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        .brand-image {
            margin-right: 8px;
            font-size: 1.4rem;
        }
        .nav-link {
            color: #475569;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: 400;
        }
        .nav-link:hover {
            background: #e5e7eb;
            color: #1e293b;
        }
        .nav-treeview .nav-link {
            padding-left: 30px;
        }
        .nav-header {
            padding: 10px 15px;
            color: #6b7280;
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: uppercase;
        }
        /* Modo oscuro */
        body.dark-mode .main-header.navbar {
            background: #1e293b;
            border-bottom: 1px solid #334155;
        }
        body.dark-mode .main-sidebar {
            background: #1e293b;
            border-right: 1px solid #334155;
        }
        body.dark-mode .content-wrapper {
            background: #111827;
        }
        body.dark-mode .main-footer {
            background: #1e293b;
            color: #e5e7eb;
            border-top: 1px solid #334155;
        }
        body.dark-mode .nav-link {
            color: #e5e7eb;
        }
        body.dark-mode .nav-link:hover {
            background: #334155;
            color: #ffffff;
        }
        body.dark-mode .brand-link {
            background: #1e293b;
            color: #e5e7eb;
            border-bottom: 1px solid #334155;
        }
        body.dark-mode .nav-header {
            color: #9ca3af;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#" id="toggleDarkMode" title="Cambiar tema">
                        <i class="fas fa-moon"></i>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user mr-1"></i> {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-light-primary elevation-4">
            <a href="#" class="brand-link">
                <i class="fas fa-cog brand-image"></i>
                <span class="brand-text font-weight-bold">Panel Admin</span>
            </a>
            <div class="sidebar">
                <nav class="mt-3">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Gestión de Tecnólogos -->
                        <li class="nav-header">Tecnólogos</li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Tecnólogos <i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('grupo.create') }}" class="nav-link">
                                        <i class="nav-icon fas fa-plus"></i>
                                        <p>Nuevo Tecnólogo/Ficha</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('grupos-fichas.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-eye"></i>
                                        <p>Ver Tecnólogos/Fichas</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Gestión de Aprendices -->
                        <li class="nav-header">Aprendices</li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-user-plus"></i>
                                <p>Aprendices <i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('register.create') }}" class="nav-link">
                                        <i class="nav-icon fas fa-plus"></i>
                                        <p>Agregar Aprendiz</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('personal.filtrado') }}" class="nav-link">
                                        <i class="nav-icon fas fa-eye"></i>
                                        <p>Ver Registrados</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Control de Asistencia -->
                        <li class="nav-header">Asistencia</li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-clock"></i>
                                <p>Ingreso y Salida <i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('entrada_salida.create') }}" class="nav-link">
                                        <i class="nav-icon fas fa-sign-in-alt"></i>
                                        <p>Registrar Entrada</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('entrada_salida.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-sign-out-alt"></i>
                                        <p>Registrar Salida</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('entradas_salidas.filtradas') }}" class="nav-link">
                                        <i class="nav-icon fas fa-search"></i>
                                        <p>Filtrar Registros</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Gestión de Recursos -->
                        <li class="nav-header">Recursos</li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tools"></i>
                                <p>Herramientas y Módulos <i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('observacion.create') }}" class="nav-link">
                                        <i class="nav-icon fas fa-plus"></i>
                                        <p>Registrar Herramienta</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('observacion.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-search"></i>
                                        <p>Filtrar Herramientas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('geo.create') }}" class="nav-link">
                                        <i class="nav-icon fas fa-water"></i>
                                        <p>Agregar Módulo</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <a href="{{route('geo.index')}}">fltro de modulos</a>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>© {{ date('Y') }} Panel de Administración</strong> - Todos los derechos reservados
        </footer>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('AdminLTE-3.2.0/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- OverlayScrollbars -->
    <script src="{{ asset('AdminLTE-3.2.0/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('AdminLTE-3.2.0/dist/js/adminlte.min.js') }}"></script>
    <!-- Scripts personalizados -->
    <script>
        $(document).ready(function () {
            const toggleDarkMode = $('#toggleDarkMode');
            toggleDarkMode.on('click', function (e) {
                e.preventDefault();
                $('body').toggleClass('dark-mode');
                const isDark = $('body').hasClass('dark-mode');
                localStorage.setItem('darkMode', isDark);
                toggleDarkMode.find('i').toggleClass('fas fa-moon fas fa-sun');
            });
            if (localStorage.getItem('darkMode') === 'true') {
                $('body').addClass('dark-mode');
                toggleDarkMode.find('i').removeClass('fa-moon').addClass('fa-sun');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>