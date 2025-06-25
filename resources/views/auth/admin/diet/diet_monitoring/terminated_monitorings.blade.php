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

    .history-buttons {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        justify-content: flex-end;
    }

    .history-button {
        background-color: #3490dc;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .history-button:hover {
        background-color: #2779bd;
    }
</style>

<div class="sowing-container">
    <h2 style="text-align: center; margin-bottom: 20px;">🐟 Siembras con seguimiento terminado</h2>

    <form method="GET" action="{{ route('sowings.index') }}" class="mb-4 p-3 rounded shadow-sm" style="background-color: #f9f9f9;">
        <div class="row">
            <div class="col-md-3 mb-2">
                <label for="pond_id">Estanque</label>
                <select class="form-control" name="pond_id" id="pond_id">
                    <option value="">Todos</option>
                    @foreach($ponds as $pond)
                    <option value="{{ $pond->id }}" {{ request('pond_id') == $pond->id ? 'selected' : '' }}>
                        {{ $pond->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-2">
                <label for="identifier_id">Identificador</label>
                <select class="form-control" name="identifier_id" id="identifier_id">
                    <option value="">Todos</option>
                    @foreach($identifiers as $identifier)
                    <option value="{{ $identifier->id }}"
                        data-pond="{{ $identifier->pond_id }}"
                        {{ request('identifier_id') == $identifier->id ? 'selected' : '' }}>
                        {{ $identifier->identificador }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-2">
                <label for="date">Fecha de siembra</label>
                <input type="date" class="form-control" name="date" id="date" value="{{ request('date') }}">
            </div>

            <div class="col-md-3 mb-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </div>
    </form>

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
            <p><strong>Estado:</strong> {{ $sowing->state }}</p><br><br>
            <h1>🟩 Historial de Seguimiento De Dieta</h1>
            <table class="sowing-table">
                <thead>
                    <tr>
                        <th>Fecha de muestreo</th>
                        <th>Peso promedio</th>
                        <th>Balance peces</th>
                        <th>% Biomasa</th>
                        <th>Biomasa</th>
                        <th>Alimento diario</th>
                        <th>Tipo alimento</th>
                        <th>Raciones</th>
                        <th>Ración</th>
                        <th>Ganancia peso</th>
                        <th>Mortalidad acumulada</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sowing->dietMonitorings as $monitoring)
                    <tr>
                        <td>{{ $monitoring->sampling_date }}</td>
                        <td>{{ rtrim(rtrim(number_format($monitoring->average_weight, 2, '.', ''), '0'), '.') }} g</td>
                        <td>{{ rtrim(rtrim(number_format($monitoring->fish_balance, 2, '.', ''), '0'), '.') }}</td>
                        <td>{{ rtrim(rtrim(number_format($monitoring->biomass_percentage, 2, '.', ''), '0'), '.') }} %</td>
                        <td>{{ rtrim(rtrim(number_format($monitoring->biomass, 2, '.', ''), '0'), '.') }} kg</td>
                        <td>{{ rtrim(rtrim(number_format($monitoring->daily_feed, 2, '.', ''), '0'), '.') }} kg</td>
                        <td>{{ $monitoring->feed_type }}</td>
                        <td>{{ rtrim(rtrim(number_format($monitoring->ration_number, 2, '.', ''), '0'), '.') }}</td>
                        <td>{{ rtrim(rtrim(number_format($monitoring->ration, 2, '.', ''), '0'), '.') }} g</td>
                        <td>{{ rtrim(rtrim(number_format($monitoring->weight_gain, 2, '.', ''), '0'), '.') }} g</td>
                        <td>{{ rtrim(rtrim(number_format($monitoring->cumulative_mortality, 2, '.', ''), '0'), '.') }}</td>

                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="history-buttons">
                <a href="{{ route('sowing.export.pdf', $sowing->id) }}" class="history-button" target="_blank">
                    📄 Descargar PDF Completo
                </a>
                <a href="{{ route('feed_records.history', $sowing->id) }}" class="history-button">
                    📊 Historial de Alimentación
                </a>
                <a href="{{ route('water_quality.history', $sowing->id) }}" class="history-button">
                    💧 Historial de Calidad del Agua
                </a>
                <a href="{{ route('mortality.history', $sowing->id) }}" class="history-button">
                    💀 Historial de Mortalidad
                </a>
            </div>
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

    document.addEventListener('DOMContentLoaded', function() {
        const pondSelect = document.getElementById('pond_id');
        const identifierSelect = document.getElementById('identifier_id');
        const selectedIdentifier = '{{ request('
        identifier_id ') }}';

        pondSelect.addEventListener('change', function() {
            const pondId = this.value;
            identifierSelect.innerHTML = '<option value="">Cargando...</option>';

            fetch(`/identifiers/by-pond/${pondId}`)
                .then(response => response.json())
                .then(data => {
                    identifierSelect.innerHTML = '<option value="">Todos</option>';
                    data.forEach(identifier => {
                        const option = document.createElement('option');
                        option.value = identifier.id;
                        option.textContent = identifier.identificador;
                        if (identifier.id == selectedIdentifier) {
                            option.selected = true;
                        }
                        identifierSelect.appendChild(option);
                    });
                })
                .catch(() => {
                    identifierSelect.innerHTML = '<option value="">Error al cargar</option>';
                });
        });

        if (pondSelect.value) {
            pondSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection