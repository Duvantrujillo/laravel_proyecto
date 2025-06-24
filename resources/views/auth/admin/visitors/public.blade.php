<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Visitante</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-header {
            background-color: #0d6efd;
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }
        h2 {
            color: #2c3e50;
            font-weight: 600;
        }
        .icon-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            color: #495057;
            margin-bottom: 5px;
        }
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
        }
        .btn-primary i {
            margin-right: 8px;
        }
        .alert {
            border-radius: 8px;
        }
        .security-question {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .security-question label {
            font-weight: 600;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-7">
                <div class="card p-4">
                    <div class="text-center mb-4">
                        <h2 class="mb-3">Registro de Visitante</h2>
                        <p class="text-muted">Complete el formulario para registrar su visita</p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <ul class="mb-0"></ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('visitors.public.store') }}">
                        @csrf
                        <input type="text" name="website" style="display:none">

                        <div class="security-question mb-4">
                            <label class="icon-label">
                                <i class="bi bi-shield-lock-fill"></i>
                                {{ session('pregunta_seguridad_texto') ?? '¿Cuánto es 1 + 1?' }}
                            </label>
                            <input type="text" name="pregunta_seguridad" class="form-control" required placeholder="Responda la pregunta">
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="icon-label"><i class="bi bi-person-fill"></i>Nombre</label>
                                <input type="text" name="name" class="form-control" required value="{{ old('name') }}" placeholder="Ingrese su nombre completo">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="icon-label"><i class="bi bi-card-text"></i>Documento</label>
                                <input type="text" name="document" class="form-control" required value="{{ old('document') }}" placeholder="Número de documento">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="icon-label"><i class="bi bi-telephone-fill"></i>Teléfono</label>
                                <input type="text" name="phone" class="form-control" required value="{{ old('phone') }}" placeholder="Número de contacto">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="icon-label"><i class="bi bi-envelope-fill"></i>Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Correo electrónico (opcional)">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="icon-label"><i class="bi bi-calendar-date-fill"></i>Fecha de Entrada</label>
                                <input type="date" name="entry_date" class="form-control" id="entry_date" readonly>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="icon-label"><i class="bi bi-clock-fill"></i>Hora de Entrada</label>
                                <input type="time" name="entry_time" class="form-control" id="entry_time" readonly>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="icon-label"><i class="bi bi-geo-alt-fill"></i>Procedencia</label>
                            <input type="text" name="origin" class="form-control" value="{{ old('origin') }}" placeholder="Empresa/Organización (opcional)">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save-fill"></i> Guardar Registro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Mostrar alertas -->
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Registro exitoso!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Hubo un problema',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Entendido'
            });
        </script>
    @endif

    <!-- Script para asignar fecha y hora -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const fecha = new Date();
            const pad = (n) => n.toString().padStart(2, '0');
            document.getElementById('entry_date').value = `${fecha.getFullYear()}-${pad(fecha.getMonth() + 1)}-${pad(fecha.getDate())}`;
            document.getElementById('entry_time').value = `${pad(fecha.getHours())}:${pad(fecha.getMinutes())}`;
        });
    </script>
</body>
</html>
