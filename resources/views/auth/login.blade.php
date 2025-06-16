@extends('layouts.app')

@section('head')
<style>
    body, html {
        height: 100%;
        margin: 0;
        overflow: hidden;
    }
    .background-carousel {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        z-index: -1;
    }
    .background-carousel img {
        width: 100%;
        height: 100vh;
        object-fit: cover;
        animation: fade 45s infinite ease-in-out;
    }
    @keyframes fade {
        0% {opacity: 1;}
        25% {opacity: 0;}
        50% {opacity: 1;}
        75% {opacity: 0;}
        100% {opacity: 1;}
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
</style>
@endsection

@section('content')
<div class="background-carousel">
    <div id="backgroundCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('login_img/background2.jpg') }}" alt="Imagen 1" />
            </div>
            <div class="carousel-item">
                <img src="{{ asset('login_img/background3.jpg') }}" alt="Imagen 2" />
            </div>
            <div class="carousel-item">
                <img src="{{ asset('login_img/background4.jpg') }}" alt="Imagen 3" />
            </div>
        </div>
    </div>
</div>

<center>
    <div class="container mt-1">
        <div class="row justify-content-center align-items-center" style="min-height: 90vh;">
            <div class="col-md-5">
                <div class="card login-card shadow-lg border-0 p-1">
                    <img src="{{ asset('login_img/logo_sena.jpg') }}" alt="Logo SENA" width="100" class="mx-auto d-block" />
                    <div class="welcome-title">¡Bienvenido a Codenest!</div>

                    <!-- Alertas bootstrap para mensajes de sesión (opcional) -->
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card-body py-4 px-3">
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Usuario</label>
                                <input id="email" type="email" class="form-control" name="email" required placeholder="Ingrese su correo" value="{{ old('email') }}" />
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input id="password" type="password" class="form-control" name="password" required placeholder="Ingrese su contraseña" />
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
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
@endsection

@section('scripts')
<script>
    // Mostrar SweetAlert2 si hay errores en validación
    @if ($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Errores encontrados',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                icon: 'error',
                confirmButtonText: 'Corregir'
            });
        });
    @endif

    // Recordar correo en localStorage
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('email');
        const savedEmail = localStorage.getItem('lastEmail');
        if (savedEmail) {
            emailInput.value = savedEmail;
        }

        document.querySelector('form').addEventListener('submit', function() {
            localStorage.setItem('lastEmail', emailInput.value);
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Acceso restringido',
            text: '{{ session('error') }}',
            confirmButtonText: 'Entendido'
        });
    </script>
@endif

@endsection
