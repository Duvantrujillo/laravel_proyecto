<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/Favicon2.png') }}" type="image/x-icon">
    <title>@yield('title', 'Panel de Administración')</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Fuentes de Google: Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- OverlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/dist/css/adminlte.min.css') }}">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Estilos personalizados -->
     <script src="https://cdn.tailwindcss.com"></script>

    @yield('styles')
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #f8f9fa;
            --text-color: #2d3748;
            --border-color: #e2e8f0;
            --hover-color: #edf2f7;
            --sidebar-width: 250px;
            --transition-speed: 0.3s;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-color);
            transition: all var(--transition-speed) ease;
        }

        .main-header.navbar {
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 10px 15px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all var(--transition-speed) ease;
        }

        .main-sidebar {
            background: #f8fafc;
            border-right: 1px solid var(--border-color);
            transition: all var(--transition-speed) ease;
        }

        .content-wrapper {
            background: var(--secondary-color);
            padding: 20px;
            min-height: calc(100vh - 101px);
            transition: all var(--transition-speed) ease;
        }

        .main-footer {
            background: #f1f5f9;
            color: #475569;
            border-top: 1px solid var(--border-color);
            padding: 12px;
            font-size: 0.9rem;
            transition: all var(--transition-speed) ease;
        }

        .brand-link {
            background: #ffffff;
            color: var(--text-color);
            border-bottom: 1px solid var(--border-color);
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 600;
            transition: all var(--transition-speed) ease;
        }

        .brand-link:hover {
            text-decoration: none;
        }

        .brand-image {
            margin-right: 10px;
            font-size: 1.5rem;
            color: var(--primary-color);
            transition: all var(--transition-speed) ease;
        }

        .nav-link {
            color: #475569;
            padding: 10px 15px;
            border-radius: 6px;
            font-weight: 500;
            margin: 3px 0;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }

        .nav-link:hover {
            background: var(--hover-color);
            color: var(--primary-color);
            transform: translateX(3px);
        }

        .nav-link.active {
            background-color: var(--primary-color);
            color: white !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
            transition: all 0.2s ease;
        }

        .nav-link:hover i {
            color: var(--primary-color);
        }

        .nav-treeview .nav-link {
            padding-left: 35px;
            font-weight: 400;
        }

        .nav-treeview .nav-link:hover {
            transform: translateX(5px);
        }

        .nav-header {
            padding: 12px 15px 5px;
            color: #6b7280;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all var(--transition-speed) ease;
        }

        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
            animation: fadeIn 0.2s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            padding: 8px 15px;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background-color: var(--hover-color);
            transform: translateX(3px);
        }

        /* Modo oscuro */
        body.dark-mode {
            --primary-color: #60a5fa;
            --secondary-color: #111827;
            --text-color: #e5e7eb;
            --border-color: #374151;
            --hover-color: #1f2937;
        }

        body.dark-mode .main-header.navbar {
            background: #1e293b;
            border-bottom: 1px solid var(--border-color);
        }

        body.dark-mode .main-sidebar {
            background: #1e293b;
            border-right: 1px solid var(--border-color);
        }

        body.dark-mode .content-wrapper {
            background: var(--secondary-color);
        }

        body.dark-mode .main-footer {
            background: #1e293b;
            color: #e5e7eb;
            border-top: 1px solid var(--border-color);
        }

        body.dark-mode .nav-link {
            color: #e5e7eb;
        }

        body.dark-mode .nav-link:hover {
            background: var(--hover-color);
            color: var(--primary-color);
        }

        body.dark-mode .brand-link {
            background: #1e293b;
            color: #e5e7eb;
            border-bottom: 1px solid var(--border-color);
        }

        body.dark-mode .nav-header {
            color: #9ca3af;
        }

        body.dark-mode .dropdown-menu {
            background-color: #1e293b;
            border-color: var(--border-color);
        }

        body.dark-mode .dropdown-item {
            color: #e5e7eb;
        }

        body.dark-mode .dropdown-item:hover {
            background-color: var(--hover-color);
            color: var(--primary-color);
        }

        /* Animaciones y efectos */
        .sidebar {
            scrollbar-width: thin;
            scrollbar-color: #c1c1c1 transparent;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background-color: #c1c1c1;
            border-radius: 3px;
        }

        body.dark-mode .sidebar::-webkit-scrollbar-thumb {
            background-color: #4b5563;
        }

        .nav-item.has-treeview>.nav-link>.right {
            transition: transform var(--transition-speed) ease;
        }

        .nav-item.has-treeview.menu-open>.nav-link>.right {
            transform: rotate(-90deg);
        }

        .nav-item.has-treeview .nav-treeview {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
            }

            to {
                opacity: 1;
                max-height: 500px;
            }
        }

        /* Efecto de carga suave */
        .content-wrapper {
            animation: fadeIn 0.5s ease;
        }

        /* Botón de modo oscuro */
        #toggleDarkMode {
            transition: all 0.3s ease;
        }

        #toggleDarkMode:hover {
            transform: rotate(15deg) scale(1.1);
        }

        /* Mejoras para el menú de usuario */
        .user-menu {
            display: flex;
            align-items: center;
        }

        .user-menu img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 8px;
        }

        /* Efecto de hover en tarjetas */
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<script>
    window.addEventListener('pageshow', function(event) {
        if (event.persisted || performance.getEntriesByType('navigation')[0]?.type === 'back_forward') {
            document.querySelectorAll('form').forEach(function(form) {
                form.reset();
            });
        }
    });
</script>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-light animate__animated animate__fadeIn">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
               
                <li class="nav-item dropdown">
                    <a class="nav-link user-menu" href="#" id="navbarDropdown" data-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fas fa-user-circle mr-1"></i>
                        @if (Auth::check())
                        <span>{{ Auth::user()->name }}</span>
                        @else
                        <script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Sesión expirada',
                                text: 'Tu sesión ha expirado. Serás redirigido al login.',
                                showConfirmButton: false,
                                timer: 3000
                            }).then(() => {
                                window.location.href = "{{ route('login') }}";
                            });
                        </script>
                        @endif

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
           <a href="#" class="brand-link animate__animated animate__fadeInLeft d-flex align-items-center">
    <i class="fas fa-fish brand-image me-2" style="color: #3498db;"></i>

    @php
        $rol = Auth::check() ? ucfirst(Auth::user()->role) : 'Invitado';
    @endphp

    <span class="brand-text">{{ $rol }}</span>
</a>

            <div class="sidebar">
                <nav class="mt-3">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Gestión de Tecnólogos -->
                        <li class="nav-header">Tecnólogos</li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Tecnólogos <i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                @auth
                                @if (auth()->user()->role === 'admin')
                                <li class="nav-item">
                                    <a href="{{ route('grupo.create') }}" class="nav-link">
                                        <i class="nav-icon fas fa-plus-circle"></i>
                                        <p>Nuevo Tecnólogo/Ficha</p>
                                    </a>
                                </li>
                                @endif
                                @endauth

                                <li class="nav-item">
                                    <a href="{{ route('grupos-fichas.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-list"></i>
                                        <p>Ver Tecnólogos/Fichas</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Gestión de Aprendices -->
                        <li class="nav-header">Aprendices</li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>Aprendices <i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('register.create') }}" class="nav-link">
                                        <i class="nav-icon fas fa-user-plus"></i>
                                        <p>Registrar Aprendiz</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('personal.filtrado') }}" class="nav-link">
                                        <i class="nav-icon fas fa-users"></i>
                                        <p>Aprendices Registrados</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Gestión de Pasantes -->
                        @auth
                        @if (auth()->user()->role === 'admin')
                        <li class="nav-header">Pasantes</li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-user-tie"></i>
                                <p>Pasantes <i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('users.create') }}" class="nav-link">
                                        <i class="nav-icon fas fa-user-plus"></i>
                                        <p>Registrar Pasante</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-search"></i>
                                        <p>Pasantes Registrados</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @endauth


                        <!-- Control de Asistencia -->
                        <li class="nav-header">Asistencia</li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-clipboard-check"></i>
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
                                        <i class="nav-icon fas fa-filter"></i>
                                        <p>Filtrar Registros</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Gestión de Recursos -->
                        <li class="nav-header">Recursos</li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-toolbox"></i>
                                <p>Herramientas <i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                @auth
                                @if (auth()->user()->role === 'admin')
                                <!-- Esto solo lo verá el administrador -->
                                <li class="nav-item">
                                    <a href="{{ route('Tool.create') }}" class="nav-link">
                                        <i class="nav-icon fas fa-plus-square"></i>
                                        <p>Registrar Herramienta</p>
                                    </a>
                                </li>
                                @endif
                                @endauth

                                <li class="nav-item">
                                    <a href="{{ route('Tool.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-search"></i>
                                        <p> Inventario de Herramientas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('loans.create') }}" class="nav-link">
                                        <i class="nav-icon fas fa-hand-holding"></i>
                                        <p>Solicitar Herramienta</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('returns.create') }}" class="nav-link">
                                        <i class="nav-icon fas fa-tools"></i>
                                        <p>Entrega de Herramientas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('returns.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-history"></i>
                                        <p>Historial de Préstamos</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Gestión Acuícola -->
                        <li class="nav-header">Acuicultura</li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-water"></i>
                                <p>Producción <i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('geo.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-map-marked-alt"></i>
                                        <p>Estanques</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('mortality.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-skull-crossbones"></i>
                                        <p>Mortalidad</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('species.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-fish"></i>
                                        <p>Especies de Peces</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Gestión de Dietas -->
                        <li class="nav-header">Alimentación</li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-utensils"></i>
                                <p>Dietas <i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('siembras.create') }}" class="nav-link">
                                        <i class="nav-icon fas fa-file-alt"></i>
                                        <p>Encabezado Dieta</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('diet_monitoring.create') }}" class="nav-link">
                                        <i class="nav-icon fas fa-clipboard-list"></i>
                                        <p>Seguimiento de Dieta</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('diet_monitoring.terminated') }}" class="nav-link">
                                        <i class="nav-icon fas fa-archive"></i>
                                        <p>Dietas Terminadas</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Invitados -->
                        <li class="nav-header">Visitas</li>
                        <li class="nav-item">
                            <a href="{{ route('visitors.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-user-friends"></i>
                                <p>Invitados</p>
                            </a>
                          <a href="{{ route('sowing.dashboard') }}" class="nav-link">
    <i class="nav-icon fas fa-chart-bar"></i>
    <p>Gráficas</p>
</a>

                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper animate__animated animate__fadeIn">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="main-footer animate__animated animate__fadeInUp">
            <strong>© {{ date('Y') }} Panel de Administración</strong> - Todos los derechos reservados
            <div class="float-right d-none d-sm-inline-block">
                <small>Versión 1.0.0</small>
            </div>
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
        $(document).ready(function() {
            const toggleDarkMode = $('#toggleDarkMode');

            // Función para alternar el modo oscuro
            function toggleDarkModeState() {
                $('body').toggleClass('dark-mode');
                const isDark = $('body').hasClass('dark-mode');
                localStorage.setItem('darkMode', isDark);

                // Cambiar icono con animación
                const icon = toggleDarkMode.find('i');
                icon.addClass('animate__animated animate__flip');
                setTimeout(() => {
                    icon.toggleClass('fa-moon fa-sun');
                    icon.attr('title', isDark ? 'Modo claro' : 'Modo oscuro');
                    icon.removeClass('animate__flip');
                }, 200);
            }

            // Manejar el clic en el botón
            toggleDarkMode.on('click', function(e) {
                e.preventDefault();
                toggleDarkModeState();
            });

            // Verificar el estado almacenado
            if (localStorage.getItem('darkMode') === 'true') {
                $('body').addClass('dark-mode');
                toggleDarkMode.find('i').removeClass('fa-moon').addClass('fa-sun');
            }

            // Resaltar elemento de menú activo
            const currentUrl = window.location.href;
            $('.nav-link').each(function() {
                if ($(this).attr('href') && currentUrl.includes($(this).attr('href'))) {
                    $(this).addClass('active');
                    $(this).parents('.has-treeview').addClass('menu-open');
                }
            });

            // Animación al abrir el menú
            $('.nav-link').hover(
                function() {
                    $(this).addClass('animate__animated animate__pulse');
                },
                function() {
                    $(this).removeClass('animate__animated animate__pulse');
                }
            );
        });
    </script>
    @yield('scripts')
  

</body>

</html>