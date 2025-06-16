@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Editar Registro de Calidad del Agua</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('water_quality.update', $quality->id) }}" method="POST" id="editForm">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="date" class="form-label">Fecha:</label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ $quality->date }}" required>
                        <small class="text-muted">Ingrese la fecha del registro</small>
                    </div>
                    <div class="col-md-6">
                        <label for="time" class="form-label">Hora:</label>
                        <input type="time" name="time" id="time" class="form-control" value="{{ $quality->time }}" required>
                        <small class="text-muted">Ingrese la hora del registro</small>
                    </div>
                </div>

                @php
                    function formatoCol($valor) {
                        return (fmod($valor, 1) === 0.0)
                            ? number_format($valor, 0, ',', '.')
                            : number_format($valor, 2, ',', '.');
                    }
                @endphp

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="ph" class="form-label">pH:</label>
                        <input type="text" name="ph" id="ph" class="form-control formato" value="{{ formatoCol($quality->ph) }}" required>
                        <small class="text-muted">Ingrese el valor del pH</small>
                    </div>
                    <div class="col-md-4">
                        <label for="temperature" class="form-label">Temperatura (°C):</label>
                        <input type="text" name="temperature" id="temperature" class="form-control formato" value="{{ formatoCol($quality->temperature) }}" required>
                        <small class="text-muted">Ingrese la temperatura del agua</small>
                    </div>
                    <div class="col-md-4">
                        <label for="ammonia" class="form-label">Amoníaco (mg/L):</label>
                        <input type="text" name="ammonia" id="ammonia" class="form-control formato" value="{{ formatoCol($quality->ammonia) }}" required>
                        <small class="text-muted">Ingrese el nivel de amoníaco</small>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="turbidity" class="form-label">Turbidez (NTU):</label>
                        <input type="text" name="turbidity" id="turbidity" class="form-control formato" value="{{ formatoCol($quality->turbidity) }}" required>
                        <small class="text-muted">Ingrese la turbidez del agua</small>
                    </div>
                    <div class="col-md-4">
                        <label for="dissolved_oxygen" class="form-label">Oxígeno Disuelto (mg/L):</label>
                        <input type="text" name="dissolved_oxygen" id="dissolved_oxygen" class="form-control formato" value="{{ formatoCol($quality->dissolved_oxygen) }}" required>
                        <small class="text-muted">Ingrese el nivel de oxígeno disuelto</small>
                    </div>
                    <div class="col-md-4">
                        <label for="nitrites" class="form-label">Nitritos (mg/L):</label>
                        <input type="text" name="nitrites" id="nitrites" class="form-control formato" value="{{ formatoCol($quality->nitrites) }}" required>
                        <small class="text-muted">Ingrese el nivel de nitritos</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="nitrates" class="form-label">Nitratos (mg/L):</label>
                    <input type="text" name="nitrates" id="nitrates" class="form-control formato" value="{{ formatoCol($quality->nitrates) }}" required>
                    <small class="text-muted">Ingrese el nivel de nitratos</small>
                </div>

                <div class="mb-3">
                    <label for="justification" class="form-label">Justificación:</label>
                    <textarea name="justification" id="justification" rows="4" class="form-control" required>{{ $quality->justification }}</textarea>
                    <small class="text-muted">Ingrese una justificación para los datos ingresados</small>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('water_quality.history', ['sowing' => $quality->sowing_id]) }}" class="btn btn-secondary">Volver</a>
                    <button type="submit" class="btn btn-success">Actualizar Registro</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: '¡Éxito!',
            text: '{{ session("success") }}',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

{{-- JS para aplicar formato colombiano correctamente --}}
<script>
    function aplicarFormatoColombiano(valor) {
        if (!valor) return '';
        let limpio = valor.replace(/\./g, '').replace(',', '.');
        let numero = parseFloat(limpio);
        if (isNaN(numero)) return valor;
        return numero.toLocaleString('es-CO', {
            minimumFractionDigits: (numero % 1 !== 0 ? 2 : 0),
            maximumFractionDigits: 2
        });
    }

    document.querySelectorAll('.formato').forEach(input => {
        // Permitir solo números, puntos y comas
        input.addEventListener('input', () => {
            input.value = input.value.replace(/[^0-9.,]/g, '');
        });

        // Al salir del campo, aplicar formato colombiano
        input.addEventListener('blur', () => {
            input.value = aplicarFormatoColombiano(input.value);
        });
    });

    // Al enviar el formulario, convertir a formato estándar (ej. 2.000,25 -> 2000.25)
    document.getElementById('editForm').addEventListener('submit', function () {
        document.querySelectorAll('.formato').forEach(input => {
            input.value = input.value.replace(/\./g, '').replace(',', '.');
        });
    });
</script>
@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error de validación',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonText: 'Corregir',
        });
    </script>
@endif

@endsection
