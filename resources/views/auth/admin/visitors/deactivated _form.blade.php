<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario No Disponible</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fff8f8 0%, #f0f8ff 100%);
        }
        .alert-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(220, 53, 69, 0.2);
            background: white;
            border-top: 8px solid #dc3545;
            animation: pulse 2s infinite alternate;
        }
        .alert-icon {
            font-size: 5rem;
            color: #dc3545;
            margin-bottom: 1.5rem;
            animation: bounce 1.5s infinite;
        }
        .alert-title {
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 1.5rem;
        }
        .alert-message {
            font-size: 1.2rem;
            color: #495057;
            max-width: 600px;
            margin: 0 auto 2rem;
        }
        .contact-info {
            background-color: #fff3f3;
            border-radius: 10px;
            padding: 15px;
            margin-top: 2rem;
        }
        @keyframes pulse {
            0% { box-shadow: 0 10px 30px rgba(220, 53, 69, 0.2); }
            100% { box-shadow: 0 10px 40px rgba(220, 53, 69, 0.3); }
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .btn-outline-danger {
            border-width: 2px;
            font-weight: 500;
            padding: 8px 25px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card alert-card text-center p-5">
                    <div class="alert-icon">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <h2 class="alert-title text-danger">FORMULARIO NO DISPONIBLE</h2>
                    <p class="alert-message">
                        El registro de visitantes se encuentra <strong>temporalmente deshabilitado</strong> debido a mantenimiento del sistema.
                        <br>Por favor, intente nuevamente más tarde.
                    </p>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <button class="btn btn-outline-danger" onclick="window.location.reload()">
                            <i class="bi bi-arrow-clockwise"></i> Reintentar
                        </button>
                        <button class="btn btn-danger">
                            <i class="bi bi-headset"></i> Contactar Soporte
                        </button>
                    </div>
                    
                    <div class="contact-info mt-4">
                        <p class="mb-1"><i class="bi bi-info-circle-fill text-danger"></i> Para urgencias, contacte al departamento de recepción</p>
                        <h5 class="text-danger mb-0"><i class="bi bi-telephone-fill"></i> +1 234-567-890</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>