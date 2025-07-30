```html
@extends('layouts.master')

@section('content')
<!-- CDN de Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<div class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-2">
                        <h2 class="h5 mb-0 fw-semibold text-center"><i class="bi bi-plus-circle me-1"></i>Registrar Herramienta</h2>
                    </div>
                    <div class="card-body p-4">
                        <form id="miFormulario" action="{{ route('Tool.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Amount Field -->
                            <div class="mb-3">
                                <label for="amount" class="form-label fw-semibold mb-1">Cantidad</label>
                                <input type="number" name="amount" id="amount" class="form-control" placeholder="Ex: 5" required>
                                @error('amount')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Product Field -->
                            <div class="mb-3">
                                <label for="product" class="form-label fw-semibold mb-1">Herramienta</label>
                                <input type="text" name="product" id="product" class="form-control" placeholder="Ex: Pala" required>
                                @error('product')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Observation Field -->
                            <div class="mb-3">
                                <label for="observation" class="form-label fw-semibold mb-1">Observación</label>
                                <textarea name="observation" id="observation" class="form-control" rows="3" placeholder="Detalles adicionales..." required></textarea>
                                @error('observation')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Image Upload Field -->
                            <div class="mb-3">
                                <label for="image" class="form-label fw-semibold mb-1">Imagen de la Herramienta (opcional)</label>
                                <div class="input-group">
                                    <input type="file" name="image" id="image" class="form-control d-none" accept="image/*">
                                    <label for="image" class="btn btn-secondary"><i class="bi bi-image me-1"></i>Seleccionar imagen</label>
                                    <div id="image-preview" class="ms-3" style="max-width: 100px; max-height: 100px;"></div>
                                </div>
                                @error('image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Extra Info Field -->
                            <div class="mb-3">
                                <label for="extra_info" class="form-label fw-semibold mb-1">Información Extra (opcional)</label>
                                <textarea name="extra_info" id="extra_info" class="form-control" rows="3" placeholder="Manual o información automática"></textarea>
                                @error('extra_info')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <input type="hidden" name="status" value="enabled">

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-center mt-3">
                                <button type="submit" class="btn btn-success" onclick="return confirmSubmit(event)">
                                    <i class="bi bi-save me-1"></i>Registrar Herramienta
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmSubmit(event) {
        event.preventDefault(); // Detiene el envío automático del formulario

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Estás a punto de registrar esta herramienta.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#136f4f',
            cancelButtonColor: '#4a5568',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar'
        }).then((firstResult) => {
            if (firstResult.isConfirmed) {
                // Segunda confirmación
                Swal.fire({
                    title: '¿Estás completamente seguro?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#136f4f',
                    cancelButtonColor: '#4a5568',
                    confirmButtonText: 'Sí, registrar',
                    cancelButtonText: 'Cancelar'
                }).then((secondResult) => {
                    if (secondResult.isConfirmed) {
                        // Enviar el formulario manualmente
                        document.getElementById("miFormulario").submit();
                    }
                });
            }
        });

        return false; // Previene el envío automático del botón
    }

    // Vista previa de la imagen
    document.getElementById('image').addEventListener('change', function(event) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = ''; // Limpiar vista previa anterior

        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100px';
                img.style.maxHeight = '100px';
                img.className = 'rounded';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });
</script>

<!-- SweetAlert2 para notificaciones -->
@if (session('success'))
    <script>
        Swal.fire({
            title: 'Registro Exitoso',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonColor: '#136f4f',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            title: 'Error',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonColor: '#136f4f',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

@if ($errors->any())
    <script>
        Swal.fire({
            title: 'Error de validación',
            text: '{{ $errors->first() }}',
            icon: 'error',
            confirmButtonColor: '#136f4f',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

<!-- Estilos personalizados -->
<style>
    .content {
        background-color: #f4f6f9;
        font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        font-size: 1rem;
    }

    .card {
        border: 1px solid #d1d3e2;
        transition: box-shadow 0.3s ease;
        border-radius: 0.35rem;
    }

    .card:hover {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .card-header.bg-primary {
        background-color: #2b3e50;
    }

    .form-label {
        color: #4a5568;
        margin-bottom: 0.25rem;
        font-size: 0.9rem;
    }

    .form-control {
        border-radius: 0.35rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        border: 1px solid #d1d3e2;
        transition: border-color 0.3s ease;
        width: 100%;
    }

    .form-control:focus {
        border-color: #2b3e50;
        box-shadow: 0 0 0 0.2rem rgba(43, 62, 80, 0.25);
    }

    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        line-height: 1.5;
        border-radius: 0.35rem;
        transition: all 0.3s ease-in-out;
    }

    .btn-success {
        background-color: #136f4f;
        border-color: #136f4f;
    }

    .btn-success:hover {
        background-color: #0e563d;
        border-color: #0e563d;
    }

    .btn-secondary {
        background-color: #4a5568;
        border-color: #4a5568;
    }

    .btn-secondary:hover {
        background-color: #3c4655;
        border-color: #3c4655;
    }

    .input-group {
        align-items: center;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    .text-danger {
        font-size: 0.85rem;
    }
</style>

@endsection
```