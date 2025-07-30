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
                        <h2 class="h5 mb-0 fw-semibold text-center"><i class="bi bi-arrow-return-left me-1"></i>Registrar Devolución</h2>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('returns.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Selección de préstamo -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold mb-1">Préstamo</label>
                                <select name="loan_id" class="form-select" aria-label="Default select example" required>
                                    <option selected value="">-- Selecciona --</option>
                                    @foreach ($loans as $loan)
                                        @php
                                            $pending = $loan->quantity - $loan->returned_quantity;
                                        @endphp
                                        @if ($pending > 0)
                                            <option value="{{ $loan->id }}">
                                                {{ $loan->item }} | {{ $loan->requester_name }} ({{ $loan->requester_id }}) | {{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }} | Pendiente: {{ $pending }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <!-- Cantidad devuelta -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold mb-1">Cantidad Devuelta</label>
                                <input type="number" name="returned_quantity" class="form-control" min="1" required
                                       value="{{ old('returned_quantity') }}">
                            </div>

                            <!-- Fecha de devolución -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold mb-1">Fecha</label>
                                <input type="datetime-local" name="return_date" class="form-control" required
                                       value="{{ old('return_date', now()->format('Y-m-d\TH:i')) }}" min="{{ now()->format('Y-m-d\TH:i') }}">
                            </div>

                            <!-- Estado o condición -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold mb-1">Estado / Descripción</label>
                                <textarea name="return_status" class="form-control" rows="3">{{ old('return_status') }}</textarea>
                            </div>

                            <!-- Cargar imagen -->
                            <div class="mb-3 position-relative">
                                <label class="form-label fw-semibold mb-1">Imagen</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="img" name="img" accept="image/*" hidden>
                                    <label class="input-group-text btn btn-outline-primary" for="img">
                                        <i class="bi bi-upload me-1"></i>Seleccionar
                                    </label>
                                    <div class="image-preview" style="display: none; position: absolute; top: 50%; transform: translateY(-50%); left: 160px; z-index: 10;">
                                        <img id="imageThumbnail" src="#" alt="Vista previa" class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #e3e6f0; background-color: #fff; padding: 2px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Oculto: Recibido por -->
                            <input type="hidden" name="received_by" value="{{ auth()->user()->name }}">

                            <!-- Botón -->
                            <div class="d-flex justify-content-center mt-3">
                                <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i>Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estilos personalizados -->
<style>
    .content {
        background-color: #f4f6f9;
        font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        font-size: 1rem;
    }

    .card {
        border: 1px solid #e3e6f0;
        transition: box-shadow 0.3s ease;
        border-radius: 0.35rem;
    }

    .card:hover {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .form-label {
        color: #4a5568;
        margin-bottom: 0.25rem;
        font-size: 0.9rem;
    }

    .form-control, .form-select {
        border-radius: 0.35rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        border: 1px solid #d1d3e2;
        transition: border-color 0.3s ease;
        width: 100%;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .form-select {
        background-color: #fff;
        font-weight: 400;
        color: #4a5568;
    }

    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        line-height: 1.5;
        border-radius: 0.35rem;
    }

    .btn-outline-primary {
        border-color: #4e73df;
        color: #4e73df;
    }

    .btn-outline-primary:hover {
        background-color: #4e73df;
        color: #fff;
    }

    .input-group-text {
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        border-radius: 0.35rem;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    .image-preview {
        display: flex;
        align-items: center;
    }

    .image-preview img {
        transition: opacity 0.3s ease;
    }
</style>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript para la vista previa de la imagen -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const fileInput = document.getElementById('img');
        const imagePreview = document.querySelector('.image-preview');
        const imageThumbnail = document.getElementById('imageThumbnail');

        fileInput.addEventListener('change', () => {
            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    imageThumbnail.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(fileInput.files[0]);
            } else {
                imagePreview.style.display = 'none';
            }
        });
    });
</script>

<!-- SweetAlert2 para notificaciones -->
@if ($errors->has('returned_quantity'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: 'Error',
            text: '{{ $errors->first('returned_quantity') }}',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif
@if (session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif
@if ($errors->any() && !$errors->has('returned_quantity'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: 'Error de validación',
            text: '{{ $errors->first() }}',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

@endsection
```