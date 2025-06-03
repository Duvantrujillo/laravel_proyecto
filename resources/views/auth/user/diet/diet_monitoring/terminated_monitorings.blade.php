@extends('layouts.master')

@section('content')
    <style>
        .card {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.01);
        }

        .card-header {
            background-color: #f5f5f5;
            color: #333;
            padding: 15px 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            flex-wrap: wrap;
        }

        .card-header-left {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .pond-info {
            font-size: 18px;
            font-weight: bold;
        }

        .pond-info span {
            font-size: 14px;
            font-weight: normal;
            color: #555;
            margin-left: 8px;
        }

        .date-info {
            font-size: 12px;
            color: #666;
        }

        .card-body {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease, padding 0.3s ease;
            background-color: #fafafa;
            padding: 0 20px;
        }

        .card-body.open {
            padding: 20px;
            max-height: 1000px;
        }

        .sowing-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 14px;
            margin-top: 10px;
        }

        .sowing-table th,
        .sowing-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .sowing-table th {
            background-color: #f1f1f1;
            font-weight: bold;
        }

        .arrow-icon {
            font-size: 20px;
            transition: transform 0.3s ease;
        }

        .arrow-icon.open {
            transform: rotate(180deg);
        }
    </style>

    <div class="sowing-container">
        <h2 style="text-align: center; margin-bottom: 20px;">🐟 Siembras con seguimiento terminado</h2>

        @foreach ($sowings as $sowing)
            <div class="card">
                <div class="card-header" onclick="toggleCard(this)">
                    <div class="card-header-left">
                        <div class="pond-info">
                            {{ $sowing->pond->name ?? 'No disponible' }}
                            <span>| Identificador: {{ $sowing->identifier->identificador ?? 'No disponible' }}</span>
                        </div>
                        <div class="date-info">
                            <strong>Inicio:</strong> {{ $sowing->sowing_date ?? 'No disponible' }} |
                            <strong>Finalización:</strong> {{ $sowing->sowing_completion ?? 'En progreso' }}
                        </div>
                    </div>
                    <span class="arrow-icon">▼</span>
                </div>

                <div class="card-body">
                    <p><strong>Estanque/Geomembrana:</strong> {{ $sowing->pond->name ?? 'No disponible' }} 
                        <strong>Identificador:</strong> {{ $sowing->identifier->identificador ?? 'No disponible' }}
                    </p>
                    <p><strong>Fecha de inicio:</strong> {{ $sowing->sowing_date ?? 'No disponible' }}</p>
                    <p><strong>Fecha de finalización:</strong> {{ $sowing->sowing_completion ?? 'En progreso' }}</p>
                    <p><strong>Especie:</strong> {{ $sowing->type->species->name ?? 'No disponible' }}</p>
                    <p><strong>Tipo:</strong> {{ $sowing->type->name ?? 'No disponible' }}</p>
                    <p><strong>Estado:</strong> {{ $sowing->state }}</p>

                    <table class="sowing-table">
                        <thead>
                            <tr>
                                <th>Fecha de muestreo</th>
                                <th>Peso promedio</th>
                                <th>Biomasa</th>
                                <th>Alimento diario</th>
                                <th>Tipo de alimento</th>
                                <th>Raciones</th>
                                <th>Ganancia de peso</th>
                                <th>Mortalidad acumulada</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sowing->dietMonitorings as $monitoring)
                                <tr>
                                    <td>{{ $monitoring->sampling_date }}</td>
                                    <td>{{ rtrim(rtrim(number_format($monitoring->average_weight, 2, '.', ''), '0'), '.') }}
                                        g</td>
                                    <td>{{ rtrim(rtrim(number_format($monitoring->biomass, 2, '.', ''), '0'), '.') }} kg
                                    </td>
                                    <td>{{ rtrim(rtrim(number_format($monitoring->daily_feed, 2, '.', ''), '0'), '.') }} kg
                                    </td>
                                    <td>{{ $monitoring->feed_type }}</td>
                                    <td>
                                        {{ $monitoring->ration_number }} x
                                        {{ rtrim(rtrim(number_format($monitoring->ration, 2, '.', ''), '0'), '.') }} g
                                    </td>
                                    <td>{{ rtrim(rtrim(number_format($monitoring->weight_gain, 2, '.', ''), '0'), '.') }} g
                                    </td>
                                    <td>{{ rtrim(rtrim(number_format($monitoring->cumulative_mortality, 2, '.', ''), '0'), '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        function toggleCard(header) {
            const cardBody = header.nextElementSibling;
            const icon = header.querySelector(".arrow-icon");

            if (cardBody.classList.contains("open")) {
                cardBody.classList.remove("open");
                icon.classList.remove("open");
                icon.textContent = "▼";
            } else {
                cardBody.classList.add("open");
                icon.classList.add("open");
                icon.textContent = "▲";
            }
        }
    </script>
@endsection
