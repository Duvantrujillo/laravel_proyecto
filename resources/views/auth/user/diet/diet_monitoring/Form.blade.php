@extends('layouts.master')

@section('content')
    <style>
        .sowing-container {
            padding: 20px;
            max-width: 100%;
            overflow-x: auto;
        }

        .sowing-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 14px;
        }

        .sowing-table th,
        .sowing-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .sowing-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .btn-action {
            padding: 6px 10px;
            font-size: 13px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-follow-up {
            background-color: #4CAF50;
            color: white;
        }

        .btn-follow-up2 {
            background-color: #1c38a6;
            color: white;
        }

        .btn-end-follow-up {
            background-color: #f44336;
            color: white;
        }

        .btn-disabled {
            background-color: #ffeb3b;
            color: #000;
            cursor: not-allowed;
        }

        /* Nuevo estilo para botón Historial Calidad de Agua */
        .btn-water-history {
            background-color: #ff9800; /* naranja */
            color: white;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .status-message {
            font-size: 12px;
            color: #d84315;
        }
    </style>

    <div class="sowing-container">
        <h2>Siembras con estado 'inicializada'</h2>

        <table class="sowing-table">
            <thead>
                <tr>
                    <th>Fecha de siembra</th>
                    <th>Biomasa Inicial</th>
                    <th>Especie</th>
                    <th>Tipo</th>
                    <th>Frecuencia de alimentación</th>
                    <th>Número de Peces</th>
                    <th>Origen</th>
                    <th>Área</th>
                    <th>Peso Inicial</th>
                    <th>Peso Total</th>
                    <th>Densidad Inicial</th>
                    <th>Estanque</th>
                    <th>Identificador Estanque</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @php
                    use App\Models\Mortality;
                @endphp

                @foreach ($sowings as $sowing)
                    <tr>
                        <td>{{ $sowing->sowing_date }}</td>
                        <td>{{ $sowing->initial_biomass }}</td>
                        <td>{{ $sowing->type->species->name ?? 'No disponible' }}</td>
                        <td>{{ $sowing->type->name ?? 'No disponible' }}</td>
                        <td>{{ $sowing->initial_feeding_frequency }}</td>
                        <td>{{ $sowing->fish_count }}</td>
                        <td>{{ $sowing->origin }}</td>
                        <td>{{ $sowing->area }}</td>
                        <td>{{ $sowing->initial_weight }}</td>
                        <td>{{ $sowing->total_weight }}</td>
                        <td>{{ $sowing->initial_density }}</td>
                        <td>{{ $sowing->pond->name ?? 'No disponible' }}</td>
                        <td>{{ $sowing->identifier->identificador ?? 'No disponible' }}</td>
                        <td>{{ $sowing->state }}</td>
                        <td>
                            <div class="action-buttons">
                                @php
                                    $lastMonitoring = $sowing->dietMonitorings->sortByDesc('created_at')->first();
                                    $lastMonitoringDate = $lastMonitoring ? $lastMonitoring->created_at : null;

                                    $mortalityCount = Mortality::where('pond_code_id', $sowing->identifier_id)
                                        ->where('sowing_id', $sowing->id)
                                        ->when($lastMonitoringDate, function ($query) use ($lastMonitoringDate) {
                                            $query->where('created_at', '>', $lastMonitoringDate);
                                        })
                                        ->count();

                                    $isFirst = $sowing->dietMonitorings->isEmpty();
                                    $canRegister = $isFirst || $mortalityCount >= 15;
                                    $remaining = 15 - $mortalityCount;
                                @endphp

                                @if ($canRegister)
                                    <a href="{{ route('diet_monitoring.index', ['sowing_id' => $sowing->id]) }}"
                                        class="btn-action btn-follow-up">
                                        Hacer seguimiento
                                    </a>
                                @else
                                    <button class="btn-action btn-disabled" disabled>
                                        Esperando registros
                                    </button>
                                    <span class="status-message">
                                        Faltan {{ $remaining }} registros de mortalidad
                                    </span>
                                @endif

                                @if ($sowing->state === 'inicializada')
                                    <a href="{{ route('sowing.diet_monitoring', ['sowing' => $sowing->id]) }}"
                                        class="btn-action btn-follow-up2">
                                        Ver seguimiento
                                    </a>

                                    <!-- Botón Calidad de Agua -->
                                    <a href="{{ route('water_quality.create', $sowing->id) }}" class="btn-action btn-follow-up2">
                                        Calidad de Agua
                                    </a>

                                    <!-- Botón Historial Calidad de Agua -->
                                    <a href="{{ route('water_quality.history', ['sowing' => $sowing->id]) }}" 
                                       class="btn-action btn-water-history">
                                       Historial Calidad de Agua
                                    </a>
                                @endif

                                <form action="{{ route('sowing.finish', $sowing->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-action btn-end-follow-up">Terminar seguimiento</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
