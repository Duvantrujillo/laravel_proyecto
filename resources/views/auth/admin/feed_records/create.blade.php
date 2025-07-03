@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Registrar Alimentación para Siembra </h2>

    {{-- Mensajes de éxito --}}
    @if ($errors->any())
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let errores = `@foreach ($errors->all() as $error){{ $error }}\n @endforeach`;
        Swal.fire({
            title: '¡Error!',
            text: errores,
            icon: 'error',
            confirmButtonText: 'Aceptar',
            backdrop: 'rgba(0,0,0,0.4)',
            customClass: {
                confirmButton: 'btn btn-danger px-4'
            }
        });
    </script>
    @endif

    @if (session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: '¡Éxito!',
            text: '{{ session("success") }}',
            icon: 'success',
            confirmButtonText: 'Aceptar',
            backdrop: 'rgba(0,0,0,0.4)',
            customClass: {
                confirmButton: 'btn btn-primary px-4'
            }
        });
    </script>
    @endif

    <form action="{{ route('feed_records.store') }}" method="POST" id="feedForm">
        @csrf

        {{-- Fecha --}}
        <div class="mb-3">
            <label for="feeding_date" class="form-label">Fecha de alimentación:</label>
            <input type="date" name="feeding_date" id="feeding_date" class="form-control" value="{{ old('feeding_date') }}" required>
        </div>

        <input type="hidden" name="diet_monitoring_id" value="{{ $dietMonitoring->id ?? '' }}">

        {{-- Raciones --}}
        @for ($i = 1; $i <= 5; $i++)
            <div class="mb-3">
            <label for="r{{ $i }}" class="form-label">
                R{{ $i }} (g):
                @if(isset($dietMonitoring))
                <small class="text-muted">Recomendado por ración: {{ number_format($dietMonitoring->ration, 2, ',', '.') }} g</small>
                @endif
            </label>
            <input
                type="text"
                name="r{{ $i }}"
                id="r{{ $i }}"
                class="form-control racion-input"
                value="{{ old('r'.$i) ?? '0,00' }}"
                autocomplete="off"
                inputmode="decimal"
                pattern="[0-9\.,]*">
</div>
@endfor

<div class="mb-3">
    <label for="daily_ration" class="form-label">Ración diaria total (suma de r1 a r5) (g):</label>
    <input type="text" name="daily_ration" id="daily_ration" class="form-control" value="{{ old('daily_ration') ?? '0,00' }}" readonly required>
</div>

@if(isset($dietMonitoring))
<div class="mb-3">
    <label class="form-label">Ración completa recomendada (Daily Feed):</label>
    <input type="text" class="form-control" value="{{ number_format($dietMonitoring->daily_feed, 2, ',', '.') }} g" readonly>
</div>
@endif

<div class="mb-3">
    <label for="crude_protein" class="form-label">Proteína cruda (%):</label>
    <input
        type="text"
        name="crude_protein"
        id="crude_protein"
        class="form-control decimal-input"
        value="{{ old('crude_protein') ?? '0,00' }}"
        autocomplete="off"
        inputmode="decimal"
        pattern="[0-9\.,]*"
        required>
</div>

{{-- Justificación --}}
<div class="mb-3">
    <label for="justification" class="form-label">
        Justificación (si la suma de raciones no coincide con la ración completa recomendada):
    </label>
    <textarea name="justification" id="justification" class="form-control">{{ old('justification') }}</textarea>
    <small class="text-muted">
        Por favor explique por qué la suma de raciones es diferente (por ejemplo, cambio de dieta, mortalidad, ajuste de peso, etc.).
    </small>
</div>

<button type="submit" class="btn btn-primary">Guardar</button>
</form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const racionInputs = document.querySelectorAll('.racion-input');
        const crudeProteinInput = document.querySelector('.decimal-input');
        const dailyRationInput = document.getElementById('daily_ration');

        function formatNumber(value) {
            if (!value) return '';
            value = value.replace(/[^\d,]/g, '');
            let parts = value.split(',');
            let integerPart = parts[0];
            let decimalPart = parts[1] || '';
            integerPart = integerPart.replace(/^0+(?=\d)/, '');
            integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            if (decimalPart.length > 2) decimalPart = decimalPart.substring(0, 2);
            return decimalPart.length > 0 ? integerPart + ',' + decimalPart : integerPart;
        }

        function parseToCents(str) {
            if (!str) return 0;
            str = str.trim().replace(/\./g, '').replace(',', '.');
            let num = parseFloat(str);
            return isNaN(num) ? 0 : Math.round(num * 100);
        }

        function formatFromCents(cents) {
            let num = (cents / 100).toFixed(2);
            let parts = num.split('.');
            let integerPart = parts[0];
            let decimalPart = parts[1];
            integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            return integerPart + ',' + decimalPart;
        }

        function updateTotal() {
            let totalCents = 0;
            racionInputs.forEach(input => {
                totalCents += parseToCents(input.value);
            });
            dailyRationInput.value = formatFromCents(totalCents);
        }

        racionInputs.forEach(input => {
            input.addEventListener('blur', () => {
                input.value = formatNumber(input.value);
                updateTotal();
            });

            // No modificamos el valor durante la escritura
            input.addEventListener('input', () => {});
            input.value = formatNumber(input.value);
        });

        if (crudeProteinInput) {
            crudeProteinInput.addEventListener('blur', () => {
                crudeProteinInput.value = formatNumber(crudeProteinInput.value);
            });

            crudeProteinInput.addEventListener('input', () => {});
            crudeProteinInput.value = formatNumber(crudeProteinInput.value);
        }

        updateTotal();
    });
</script>
@endsection