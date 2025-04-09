@extends('layouts.master')

@section('content')

<!-- Contenedor principal -->
<div class="container-fluid tool-shed-container">
    <!-- Título con animación -->
    <h1 class="text-center animate__animated animate__zoomIn">Bodega de Herramientas</h1>

    <!-- Formulario centrado con animación de entrada -->
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <form action="{{ route('observacion.store') }}" method="POST" class="tool-form animate__animated animate__fadeInUp" onsubmit="return confirmarenvio()">
                @csrf

                <!-- Campo Cantidad -->
                <div class="form-group">
                    <label for="amount" class="form-label">Cantidad</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                        <input type="number" name="amount" id="amount" class="form-control" placeholder="Ej: 5" required>
                    </div>
                </div>

                <!-- Campo Producto -->
                <div class="form-group">
                    <label for="product" class="form-label">Producto</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-tools"></i></span>
                        <input type="text" name="product" id="product" class="form-control" placeholder="Ej: Martillo" required>
                    </div>
                </div>

                <!-- Campo Observación -->
                <div class="form-group">
                    <label for="observation" class="form-label">Observación</label>
                    <textarea name="observation" id="observation" class="form-control" rows="4" placeholder="Detalles adicionales..." required></textarea>
                </div>

                <!-- Botón de envío con animación -->
                <button type="submit" class="btn btn-submit">Enviar <i class="fas fa-paper-plane"></i></button>
            </form>
        </div>
    </div>
</div>


<script>
    function confirmarenvio(){
        return confirm("esta seguro de enviar esta herramienta")
    }
</script>

@if (session('error'))    


<script>
alert ("{{session('error')}}");
</script>
    
@endif

<!-- Dependencias -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    /* Estilos personalizados */
    .tool-shed-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        padding-top: 50px;
        position: relative;
        overflow: hidden;
    }

    h1 {
        color: #ffffff;
        font-size: 3rem;
        font-weight: 700;
        text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        margin-bottom: 40px;
    }

    .tool-form {
        background: rgba(255, 255, 255, 0.95);
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(5px);
    }

    .form-label {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #dcdcdc;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #2a5298;
        box-shadow: 0 0 12px rgba(42, 82, 152, 0.4);
    }

    .input-group-text {
        background-color: #2a5298;
        color: #fff;
        border: none;
        border-radius: 8px 0 0 8px;
    }

    .btn-submit {
        width: 100%;
        background-color: #e74c3c;
        border: none;
        padding: 12px;
        font-size: 1.2rem;
        border-radius: 8px;
        color: #fff;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        background-color: #c0392b;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
    }

    .btn-submit:active {
        transform: translateY(1px);
    }

    /* Efecto de partículas en el fondo (opcional) */
    .tool-shed-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 800"><circle cx="400" cy="400" r="200" fill="rgba(255,255,255,0.1)"/></svg>');
        opacity: 0.1;
        animation: float 10s infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
</style>

@endsection