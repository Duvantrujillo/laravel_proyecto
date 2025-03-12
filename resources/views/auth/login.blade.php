@extends('layouts.app')

@section('content')
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Codenest - Iniciar Sesión</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <style>
            body,
            html {
                height: 100%;
                margin: 0;
                overflow: hidden;

            }


            .background-carousel {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1;
            }

            .background-carousel img {
                width: 100%;
                height: 100vh;
                object-fit: cover;
                animation: fade 45s infinite ease-in-out;
            }

            @keyframes fade {
                0% {
                    opacity: 1;
                }

                25% {
                    opacity: 0;
                }

                50% {
                    opacity: 1;
                }

                75% {
                    opacity: 0;
                }

                100% {
                    opacity: 1;
                }
            }

            .login-card {
                background: rgba(231, 232, 238, 0.85);
                backdrop-filter: blur(30px);
                border-radius: 20px;
                max-width: 400px;

            }

            .welcome-title {
                font-size: 1.5rem;
                font-weight: bold;
                color: rgb(17, 61, 126);
                text-align: center;
                margin-bottom: 1rem;
            }

            .navbar {
                color: rgba(26, 49, 184, 0.85);
            }
        </style>
    </head>

    <body>
        <!-- Carrusel de fondo -->
        <div class="background-carousel">
            <div id="backgroundCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('login_img/background2.jpg') }}" alt="Imagen 1">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('login_img/background3.jpg') }}" alt="Imagen 1">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('login_img/background4.jpg') }}" alt="Imagen 1">
                    </div>
                </div>
            </div>
        </div>

        <!-- Navbar -->



        <center>
            <!-- Contenido del Login --><br><br>
            <div class="container mt-1">
                <div class="row justify-content-center align-items-center" style="min-height: 90vh;">
                    <div class="col-md-5">
                        <div class="card login-card shadow-lg border-0 p-1">
                            <img src="{{ asset('login_img/logo_sena.jpg') }}" alt="Logo SENA" width="100"
                                class="mx-auto d-block">
                            <div class="welcome-title">¡Bienvenido A Codenest!</div>
                            <div class="card-body py-4 px-3">
                                <form action="{{ route('login') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Usuario</label>
                                        <input id="email" type="email" class="form-control" name="email" required
                                            placeholder="Ingrese su correo">
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Contraseña</label>
                                        <input id="password" type="password" class="form-control" name="password" required
                                            placeholder="Ingrese su contraseña">
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                            <label class="form-check-label" for="remember">Recuérdame</label>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">Ingresar</button>

                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center py-2">
                                <small>Plataforma de Aprendizaje - SENA</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </center>
    </body>

    </html>
@endsection
