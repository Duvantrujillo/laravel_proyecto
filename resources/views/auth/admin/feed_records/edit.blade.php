@extends('layouts.master')

@section('content')
<div style="max-width: 750px; margin: auto; padding: 20px;">
    <h2 style="text-align: center; margin-bottom: 25px;">Editar Registro de Alimentación</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin-bottom: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('feed_records.update', $record->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="feeding_date">Fecha de alimentación:</label>
        <input type="date" name="feeding_date" id="feeding_date" value="{{ old('feeding_date', $record->feeding_date->format('Y-m-d')) }}" required class="form-control mb-3">

        @foreach (['r1' => 'Ración 1', 'r2' => 'Ración 2', 'r3' => 'Ración 3', 'r4' => 'Ración 4', 'r5' => 'Ración 5'] as $field => $label)
            @php
                $rawValue = old($field, $record->$field);
                $floatVal = floatval(str_replace(',', '.', $rawValue));
                $formatted = (fmod($floatVal, 1.0) == 0.0)
                    ? number_format($floatVal, 0, ',', '.')
                    : number_format($floatVal, 2, ',', '.');
            @endphp
            <label for="{{ $field }}">{{ $label }} (g):</label>
            <input type="text" name="{{ $field }}" id="{{ $field }}" value="{{ $formatted }}" required class="form-control mb-3 ration-input">
        @endforeach

        <label for="crude_protein">Proteína cruda (%):</label>
        @php
            $rawValue = old('crude_protein', $record->crude_protein);
            $floatVal = floatval(str_replace(',', '.', $rawValue));
            $formattedProtein = (fmod($floatVal, 1.0) == 0.0)
                ? number_format($floatVal, 0, ',', '.')
                : number_format($floatVal, 2, ',', '.');
        @endphp
        <input type="text" name="crude_protein" id="crude_protein" value="{{ $formattedProtein }}" required class="form-control mb-3">

        <label for="justification">Justificación:</label>
        <textarea name="justification" id="justification" rows="4" class="form-control mb-4">{{ old('justification') }}</textarea>

        <div style="display: flex; justify-content: space-between;">
            <a href="{{ route('feed_records.history', ['sowingId' => $record->dietMonitoring->sowing_id]) }}" class="btn btn-secondary">Volver</a>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.ration-input, #crude_protein').forEach(function(input) {
        input.addEventListener('input', function(e) {
            let raw = this.value.replace(/\./g, '').replace(',', '.');

            if (!isNaN(raw) && raw !== '') {
                let num = parseFloat(raw);
                let formatted = num % 1 === 0
                    ? num.toLocaleString('es-CO', { minimumFractionDigits: 0, maximumFractionDigits: 0 })
                    : num.toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                this.value = formatted;
            }
        });
    });
</script>

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
@endsection
