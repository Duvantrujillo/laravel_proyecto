@extends('layouts.master')

@section('content')
    <style>
        .form-container {
            max-width: 900px;
            margin: auto;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>

    <div class="form-container">
        <h2>Registro de Seguimiento de Dieta</h2>

        <form action="{{ route('diet_monitoring.store') }}" method="POST" id="followup-form">
            @csrf
            <input type="hidden" name="sowing_id" value="{{ $sowing->id }}">

            <div class="form-group">
                <label>Fecha de muestreo</label>
                <input type="date" name="sampling_date" required>
            </div>

            <div class="form-group">
                <label>Peso promedio (gr)</label>
                <input type="number" step="0.01" name="average_weight" id="average_weight" required>
            </div>

            <div class="form-group">
                <label>Mortalidad acumulada</label>
                <input type="number" name="cumulative_mortality" id="cumulative_mortality" readonly>
            </div>

            <div class="form-group">
                <label>Saldo de peces</label>
                <input type="number" name="fish_balance" id="fish_balance" required>
            </div>

            <div class="form-group">
                <label>% Biomasa</label>
                <input type="number" step="0.01" name="biomass_percentage" id="biomass_percentage" required>
            </div>

            <div class="form-group">
                <label>Biomasa (kg)</label>
                <input type="number" step="0.01" name="biomass" id="biomass" readonly>
            </div>

            <div class="form-group">
                <label>Alimento día (gr)</label>
                <input type="number" step="0.01" name="daily_feed" id="daily_food" readonly>
            </div>

            <div class="form-group">
                <label>Número de raciones</label>
                <input type="number" name="ration_number" id="ration_number" required>
            </div>

            <div class="form-group">
                <label>Ración (gr)</label>
                <input type="number" step="0.01" name="ration" id="ration" readonly>
            </div>

            <div class="form-group">
                <label>Ganancia W (gr)</label>
                <input type="number" step="0.01" name="weight_gain" id="weight_gain" readonly>
            </div>

            <div class="form-group">
                <label>Tipo de alimento</label>
                <input type="text" name="feed_type" required>
            </div>

            <button type="submit">Guardar seguimiento</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const isFirstRecord = {{ $isFirst ? 'true' : 'false' }};
            const sowing = @json($sowing);
            const lastMonitoring = @json($lastMonitoring);
            const cumulativeMortality = {{ $isFirst ? 0 : $cumulativeMortality }};
            const fishBalanceFromServer = {{ $isFirst ? $sowing->fish_count : $fishBalance }};

            const avgWeightInput = document.getElementById('average_weight');
            const fishBalanceInput = document.getElementById('fish_balance');
            const biomassPercentageInput = document.getElementById('biomass_percentage');
            const rationNumberInput = document.getElementById('ration_number');
            const biomassInput = document.getElementById('biomass');
            const dailyFoodInput = document.getElementById('daily_food');
            const rationInput = document.getElementById('ration');
            const cumulativeMortalityInput = document.getElementById('cumulative_mortality');
            const weightGainInput = document.getElementById('weight_gain');

            if (isFirstRecord) {
                avgWeightInput.value = sowing.initial_weight || 0;
                fishBalanceInput.value = sowing.fish_count || 0;
                biomassPercentageInput.value = sowing.initial_biomass || 0;
                rationNumberInput.value = sowing.initial_feeding_frequency || 1;
                cumulativeMortalityInput.value = 0;
                calculateAll();
            } else {
                cumulativeMortalityInput.value = cumulativeMortality;
                fishBalanceInput.value = fishBalanceFromServer;
                weightGainInput.value = (parseFloat(avgWeightInput.value || 0) - parseFloat(lastMonitoring
                    .average_weight || 0)).toFixed(2);
            }

            function calculateAll() {
                const weight = parseFloat(avgWeightInput.value) || 0;
                const fishes = parseFloat(fishBalanceInput.value) || 0;
                const percentage = parseFloat(biomassPercentageInput.value) || 0;
                const rations = parseFloat(rationNumberInput.value) || 1;

                const biomass = (weight * fishes) / 1000;
                const foodPerDay = (weight * fishes) * (percentage / 100);
                const ration = foodPerDay / rations;

                biomassInput.value = biomass.toFixed(2);
                dailyFoodInput.value = foodPerDay.toFixed(2);
                rationInput.value = ration.toFixed(2);
            }

            [avgWeightInput, fishBalanceInput, biomassPercentageInput, rationNumberInput].forEach(input => {
                input.addEventListener('input', calculateAll);
            });

            avgWeightInput.addEventListener('input', () => {
                if (!isFirstRecord && lastMonitoring) {
                    const lastWeight = parseFloat(lastMonitoring.average_weight || 0);
                    const currentWeight = parseFloat(avgWeightInput.value || 0);
                    const gain = currentWeight - lastWeight;
                    weightGainInput.value = gain.toFixed(2);
                }
            });
        });
    </script>
    @if (session('error'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: '¡Error!',
                text: '{{ session('error') }}',
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
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route("diet_monitoring.create") }}';  // Cambia esta ruta por la que desees
            }
        });
    </script>
@endif
@endsection
