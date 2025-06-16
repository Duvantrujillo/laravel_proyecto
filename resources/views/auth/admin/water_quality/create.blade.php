@extends('layouts.master')

@section('content')
<div class="elegant-water-quality">
    <!-- Encabezado minimalista -->
    <div class="elegant-header">
        <h1 class="elegant-title">
            <span class="elegant-main-title">Registro de Calidad de Agua</span>
        </h1>
    </div>

    <!-- Mensajes de estado -->
    @if(session('error'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: '¡Error!',
            text: '{{ session("error") }}',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    </script>
    @endif
@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Error de validación',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#d33',
            });
        });
    </script>
@endif

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


    <!-- Formulario minimalista -->
    <form class="elegant-form" action="{{ route('water_quality.store', $sowing->id) }}" method="POST">
        @csrf

        <!-- Grupo de campos -->
        <div class="elegant-form-group">
            <label class="elegant-label">Fecha</label>
            <input type="date" name="date" value="{{ old('date') }}" class="elegant-input" >
            @error('date') <div class="elegant-error">{{ $message }}</div> @enderror
        </div>

        <div class="elegant-form-group">
            <label class="elegant-label">Hora</label>
            <input type="time" name="time" value="{{ old('time') }}" class="elegant-input" >
            @error('time') <div class="elegant-error">{{ $message }}</div> @enderror
        </div>

        <!-- Parámetros en dos columnas -->
        <div class="elegant-columns">
            <div class="elegant-column">
                <div class="elegant-form-group">
                    <label class="elegant-label">pH</label>
                    <input type="number" step="0.01" name="ph" value="{{ old('ph') }}" class="elegant-input" >
                    @error('ph') <div class="elegant-error">{{ $message }}</div> @enderror
                </div>

                <div class="elegant-form-group">
                    <label class="elegant-label">Temperatura (°C)</label>
                    <input type="number" step="0.01" name="temperature" value="{{ old('temperature') }}" class="elegant-input" >
                    @error('temperature') <div class="elegant-error">{{ $message }}</div> @enderror
                </div>

                <div class="elegant-form-group">
                    <label class="elegant-label">Amonio (mg/L)</label>
                    <input type="number" step="0.01" name="ammonia" value="{{ old('ammonia') }}" class="elegant-input" >
                    @error('ammonia') <div class="elegant-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="elegant-column">
                <div class="elegant-form-group">
                    <label class="elegant-label">Turbidez (NTU)</label>
                    <input type="number" step="0.01" name="turbidity" value="{{ old('turbidity') }}" class="elegant-input" >
                    @error('turbidity') <div class="elegant-error">{{ $message }}</div> @enderror
                </div>

                <div class="elegant-form-group">
                    <label class="elegant-label">Oxígeno Disuelto (mg/L)</label>
                    <input type="number" step="0.01" name="dissolved_oxygen" value="{{ old('dissolved_oxygen') }}" class="elegant-input" >
                    @error('dissolved_oxygen') <div class="elegant-error">{{ $message }}</div> @enderror
                </div>

                <div class="elegant-form-group">
                    <label class="elegant-label">Nitritos (mg/L)</label>
                    <input type="number" step="0.01" name="nitrites" value="{{ old('nitrites') }}" class="elegant-input" >
                    @error('nitrites') <div class="elegant-error">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="elegant-form-group">
            <label class="elegant-label">Nitratos (mg/L)</label>
            <input type="number" step="0.01" name="nitrates" value="{{ old('nitrates') }}" class="elegant-input" >
            @error('nitrates') <div class="elegant-error">{{ $message }}</div> @enderror
        </div>
        <div class="elegant-form-group">
            <label class="elegant-label">Justification (if needed)</label>
            <textarea name="justification" class="elegant-input">{{ old('justification') }}</textarea>
            @error('justification') <div class="elegant-error">{{ $message }}</div> @enderror
        </div>


        <div class="elegant-form-group">
            <label class="elegant-label">Responsable</label>
            <input type="email" name="responsible" value="{{ auth()->user()->email }}" class="elegant-input" readonly>
        </div>

        <div class="elegant-submit">
            <button type="submit" class="elegant-button">Guardar Registro</button>
        </div>
    </form>
</div>

<style>
    /* Estilos minimalistas */
    .elegant-water-quality {
        max-width: 800px;
        margin: 40px auto;
        padding: 0 20px;
        font-family: 'Helvetica Neue', Arial, sans-serif;
        color: #333;
    }

    .elegant-header {
        margin-bottom: 40px;
        text-align: center;
    }

    .elegant-main-title {
        display: block;
        font-size: 28px;
        font-weight: 300;
        letter-spacing: 0.5px;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .elegant-subtitle {
        display: block;
        font-size: 16px;
        color: #7f8c8d;
        font-weight: 400;
    }

    .elegant-message {
        padding: 15px;
        margin-bottom: 30px;
        border-radius: 4px;
        text-align: center;
        font-size: 15px;
    }

    .elegant-message.success {
        background-color: #f0f9eb;
        color: #67c23a;
        border: 1px solid #e1f3d8;
    }

    .elegant-message.error {
        background-color: #fef0f0;
        color: #f56c6c;
        border: 1px solid #fde2e2;
    }

    .elegant-form {
        background: #fff;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.05);
    }

    .elegant-form-group {
        margin-bottom: 25px;
    }

    .elegant-label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        color: #666;
        font-weight: 500;
    }

    .elegant-input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #dcdfe6;
        border-radius: 4px;
        font-size: 14px;
        color: #606266;
        transition: border-color 0.2s;
        background-color: #fff;
    }

    .elegant-input:focus {
        outline: none;
        border-color: #409eff;
    }

    .elegant-input[readonly] {
        background-color: #f5f7fa;
        cursor: not-allowed;
    }

    .elegant-error {
        color: #f56c6c;
        font-size: 12px;
        margin-top: 5px;
    }

    .elegant-columns {
        display: flex;
        gap: 30px;
    }

    .elegant-column {
        flex: 1;
    }

    .elegant-submit {
        text-align: right;
        margin-top: 30px;
    }

    .elegant-button {
        background-color: #2c3e50;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        letter-spacing: 0.5px;
    }

    .elegant-button:hover {
        background-color: #34495e;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .elegant-columns {
            flex-direction: column;
            gap: 0;
        }

        .elegant-form {
            padding: 30px 20px;
        }
    }
</style>
@endsection