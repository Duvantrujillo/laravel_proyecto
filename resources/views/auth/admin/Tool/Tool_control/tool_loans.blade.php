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
                        <h2 class="h5 mb-0 fw-semibold text-center"><i class="bi bi-box-arrow-up-right me-1"></i>Registrar Préstamo</h2>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('loans.store') }}" method="POST">
                            @csrf

                            <!-- Herramienta -->
                            <div class="mb-3">
                                <label for="tool_id" class="form-label fw-semibold mb-1">Herramienta</label>
                                <select name="tool_id" id="tool_id" class="form-select" required>
                                    <option value="-1" selected disabled>Seleccione...</option>
                                    @foreach ($items as $item)
                                        @if ($item->amount > 0)
                                            <option value="{{ $item->id }}" {{ old('tool_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->product }} (Disponibles: {{ $item->amount }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('tool_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Cantidad -->
                            <div class="mb-3">
                                <label for="quantity" class="form-label fw-semibold mb-1">Cantidad</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" min="1" required
                                       value="{{ old('quantity') }}" placeholder="Ingrese la cantidad">
                                @error('quantity')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Fecha de préstamo -->
                            <div class="mb-3">
                                <label for="loan_date" class="form-label fw-semibold mb-1">Fecha</label>
                                <input type="datetime-local" name="loan_date" id="loan_date" class="form-control" required
                                       value="{{ old('loan_date', now()->format('Y-m-d\TH:i')) }}">
                                @error('loan_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Nombre del solicitante -->
                            <div class="mb-3">
                                <label for="requester_name" class="form-label fw-semibold mb-1">Nombre del Solicitante</label>
                                <input type="text" name="requester_name" id="requester_name" class="form-control" required
                                       value="{{ old('requester_name') }}" placeholder="Ingrese el nombre del solicitante">
                                @error('requester_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Cédula del solicitante -->
                            <div class="mb-3">
                                <label for="requester_id" class="form-label fw-semibold mb-1">Cédula del Solicitante</label>
                                <input type="text" name="requester_id" id="requester_id" class="form-control" required
                                       value="{{ old('requester_id') }}" placeholder="Ingrese la cédula">
                                @error('requester_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Estado del préstamo -->
                            <div class="mb-3">
                                <label for="loan_status" class="form-label fw-semibold mb-1">Estado / Descripción</label>
                                <textarea name="loan_status" id="loan_status" class="form-control" rows="3"
                                          placeholder="Describe el estado del préstamo (opcional)">{{ old('loan_status') }}</textarea>
                                @error('loan_status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Entregado por (oculto) -->
                            <input type="hidden" name="delivered_by" value="{{ auth()->user()->name }}">

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

    .text-danger {
        font-size: 0.85rem;
    }
</style>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 para notificaciones -->
@if (session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif
@if ($errors->any())
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
@if (session('error'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: 'Error',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const requesterIdInput = document.getElementById('requester_id');
        const requesterNameInput = document.getElementById('requester_name');

        requesterIdInput.addEventListener('input', function () {
            const cedula = this.value.trim();

            if (cedula.length > 3) { // Evita consultas con muy pocos dígitos
                fetch(`/check-requester/${cedula}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            requesterNameInput.value = data.name;
                            requesterNameInput.setAttribute('readonly', true);
                        } else {
                            requesterNameInput.value = '';
                            requesterNameInput.removeAttribute('readonly');
                        }
                    })
                    .catch(error => {
                        console.error('Error al buscar cédula:', error);
                    });
            } else {
                requesterNameInput.value = '';
                requesterNameInput.removeAttribute('readonly');
            }
        });
    });
</script>

@endsection
```