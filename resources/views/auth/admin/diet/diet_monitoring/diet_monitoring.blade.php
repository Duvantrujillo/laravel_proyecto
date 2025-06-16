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

        /* Alineación para números */
        input[type="text"].number-format {
            text-align: right;
            font-variant-numeric: tabular-nums;
            font-size: 15px;
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

            <!-- Cambié a input text para formatear -->
            <div class="form-group">
                <label>Peso promedio (gr)</label>
                <input type="text" name="average_weight" id="average_weight" class="number-format" required>
            </div>

            <div class="form-group">
                <label>Mortalidad acumulada</label>
                <input type="text" name="cumulative_mortality" id="cumulative_mortality" class="number-format" readonly>
            </div>

            <div class="form-group">
                <label>Saldo de peces</label>
                <input type="text" name="fish_balance" id="fish_balance" class="number-format" required>
            </div>

            <div class="form-group">
                <label>% Biomasa</label>
                <input type="text" name="biomass_percentage" id="biomass_percentage" class="number-format" required>
            </div>

            <div class="form-group">
                <label>Biomasa (kg)</label>
                <input type="text" name="biomass" id="biomass" class="number-format" readonly>
            </div>

            <div class="form-group">
                <label>Alimento día (gr)</label>
                <input type="text" name="daily_feed" id="daily_food" class="number-format" readonly>
            </div>

            <div class="form-group">
                <label>Número de raciones</label>
                <input type="text" name="ration_number" id="ration_number" class="number-format" required>
            </div>

            <div class="form-group">
                <label>Ración (gr)</label>
                <input type="text" name="ration" id="ration" class="number-format" readonly>
            </div>

            <div class="form-group">
                <label>Ganancia W (gr)</label>
                <input type="text" name="weight_gain" id="weight_gain" class="number-format" readonly>
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

            // Función para parsear números del formato es-CO a float JS
            function parseFormattedNumber(value) {
                if (!value) return 0;
                // Elimina puntos de miles y cambia coma por punto decimal
                return parseFloat(value.replace(/\./g, '').replace(',', '.')) || 0;
            }

            // Función para formatear números según es-CO y sin decimales innecesarios
            function formatSmart(value) {
                const num = Number(value);
                if (Number.isInteger(num)) {
                    return num.toLocaleString('es-CO');
                } else {
                    return num.toLocaleString('es-CO', {
                        minimumFractionDigits: 1,
                        maximumFractionDigits: 2
                    });
                }
            }

            if (isFirstRecord) {
                avgWeightInput.value = formatSmart(sowing.initial_weight || 0);
                fishBalanceInput.value = formatSmart(sowing.fish_count || 0);
                biomassPercentageInput.value = formatSmart(sowing.initial_biomass || 0);
                rationNumberInput.value = formatSmart(sowing.initial_feeding_frequency || 1);
                cumulativeMortalityInput.value = formatSmart(0);
                calculateAll();
            } else {
                cumulativeMortalityInput.value = formatSmart(cumulativeMortality);
                fishBalanceInput.value = formatSmart(fishBalanceFromServer);
                // Si el campo avgWeightInput tiene valor (en texto formateado), parsear para cálculo
                const lastW = parseFloat(lastMonitoring.average_weight || 0);
                const currW = parseFormattedNumber(avgWeightInput.value);
                weightGainInput.value = formatSmart(currW - lastW);
            }

            function calculateAll() {
                const weight = parseFormattedNumber(avgWeightInput.value);
                const fishes = parseFormattedNumber(fishBalanceInput.value);
                const percentage = parseFormattedNumber(biomassPercentageInput.value);
                const rations = parseFormattedNumber(rationNumberInput.value) || 1;

                const biomass = (weight * fishes) / 1000;
                const foodPerDay = (weight * fishes) * (percentage / 100);
                const ration = foodPerDay / rations;

                biomassInput.value = formatSmart(biomass);
                dailyFoodInput.value = formatSmart(foodPerDay);
                rationInput.value = formatSmart(ration);
            }

            // Calcular en cada input los valores y formatear en blur
            [avgWeightInput, fishBalanceInput, biomassPercentageInput, rationNumberInput].forEach(input => {
                input.classList.add('number-format');

                input.addEventListener('input', () => {
                    calculateAll();

                    // Ganancia peso sólo si no es el primer registro
                    if (!isFirstRecord && lastMonitoring && input.id === 'average_weight') {
                        const lastWeight = parseFloat(lastMonitoring.average_weight || 0);
                        const currentWeight = parseFormattedNumber(avgWeightInput.value);
                        const gain = currentWeight - lastWeight;
                        weightGainInput.value = formatSmart(gain);
                    }
                });

                input.addEventListener('blur', () => {
                    // Formatear al perder foco
                    const num = parseFormattedNumber(input.value);
                    input.value = formatSmart(num);
                });
            });

            // Limpiar formato antes de enviar para enviar números puros
            document.getElementById('followup-form').addEventListener('submit', () => {
                [avgWeightInput, fishBalanceInput, biomassPercentageInput, rationNumberInput].forEach(input => {
                    input.value = parseFormattedNumber(input.value);
                });
            });
        });
    </script>

    @if (session('error'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: '¡Error!',
                text: "{{ session('error') }}",
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
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("diet_monitoring.create") }}';
                }
            });
        </script>
    @endif
@endsection
