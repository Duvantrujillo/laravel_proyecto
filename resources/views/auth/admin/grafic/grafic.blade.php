@extends('layouts.master') 

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Comparar Siembras Detalladamente</h1>

    {{-- Mostrar errores --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario para seleccionar dos siembras --}}
    <form action="{{ route('sowing.compare') }}" method="GET" class="mb-4 p-3 bg-light rounded shadow-sm">
        <div class="mb-3">
            <label class="form-label fw-bold">Selecciona dos siembras para comparar:</label>
            <select name="sowing_ids[]" class="form-select" multiple required>
                @foreach($sowings as $sowing)
                    <option value="{{ $sowing->id }}">
                        {{ $sowing->identifier->code ?? 'Sin ID' }} - {{ $sowing->sowing_date }}
                    </option>
                @endforeach
            </select>
            <div class="form-text">Usa Ctrl (Windows) o Cmd (Mac) para seleccionar dos.</div>
        </div>
        <button class="btn btn-primary">Comparar</button>
    </form>

    {{-- RESULTADOS DE COMPARACIÓN --}}
    @if(isset($selectedSowings) && count($selectedSowings) === 2)
        <div class="row g-4 mb-4">
            @foreach($selectedSowings as $sowing)
            @php
                $formatColombian = function($value) {
                    return fmod($value, 1) === 0.0 
                        ? number_format($value, 0, ',', '.') 
                        : number_format($value, 2, ',', '.');
                };
            @endphp
            <div class="col-md-6">
                <div class="card h-100 shadow">
                    <div class="card-header bg-success text-white fw-bold">
                        {{ $sowing->identifier->code ?? 'Sin código' }}
                    </div>
                    <div class="card-body">
                        <h5 class="card-title mb-3">{{ $sowing->species->name ?? 'Especie desconocida' }}</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Fecha de siembra: <strong>{{ $sowing->sowing_date }}</strong></li>
                            <li class="list-group-item">Final de siembra: {{ $sowing->sowing_completion ?? 'N/A' }}</li>
                            <li class="list-group-item">Peces sembrados: {{ number_format($sowing->fish_count, 0, ',', '.') }}</li>
                            <li class="list-group-item">Peso total: {{ $formatColombian($sowing->total_weight) }} g</li>
                            <li class="list-group-item">Peso inicial: {{ $formatColombian($sowing->initial_weight) }} g</li>
                            <li class="list-group-item">Biomasa inicial: {{ $formatColombian($sowing->initial_biomass) }} g</li>
                            <li class="list-group-item">Densidad inicial: {{ $formatColombian($sowing->initial_density) }}</li>
                            <li class="list-group-item">Área: {{ $formatColombian($sowing->area) }} m²</li>
                            <li class="list-group-item">Origen: {{ $sowing->origin }}</li>
                            <li class="list-group-item">Frecuencia alimenticia: {{ $formatColombian($sowing->initial_feeding_frequency) }}</li>
                            <li class="list-group-item">Estanque: {{ $sowing->pond->name ?? 'No asignado' }}</li>
                            <li class="list-group-item">Estado: {{ ucfirst($sowing->state) }}</li>
                            <li class="list-group-item text-danger">Total de muertes: <strong>{{ number_format($sowing->mortalities->sum('amount'), 0, ',', '.') }}</strong></li>
                            <li class="list-group-item text-success">Total alimento suministrado: <strong>{{ $formatColombian($sowing->dietMonitorings->sum('ration')) }} g</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- GRÁFICA COMPARATIVA --}}
        <div class="row mb-5">
            <div class="col-md-12">
                <h4 class="fw-bold text-center">Gráfica Comparativa General</h4>
                <canvas id="sowingCharts" height="100"></canvas>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
@if(isset($selectedSowings) && count($selectedSowings) === 2)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('sowingCharts').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                '{{ $selectedSowings[0]->identifier->code ?? "Siembra 1" }}',
                '{{ $selectedSowings[1]->identifier->code ?? "Siembra 2" }}'
            ],
            datasets: [
                {
                    label: 'Peces sembrados',
                    data: [{{ $selectedSowings[0]->fish_count }}, {{ $selectedSowings[1]->fish_count }}],
                    backgroundColor: '#4caf50'
                },
                {
                    label: 'Muertes totales',
                    data: [{{ $selectedSowings[0]->mortalities->sum('amount') }},
                           {{ $selectedSowings[1]->mortalities->sum('amount') }}],
                    backgroundColor: '#f44336'
                },
                {
                    label: 'Alimento total (g)',
                    data: [{{ $selectedSowings[0]->dietMonitorings->sum('ration') }},
                           {{ $selectedSowings[1]->dietMonitorings->sum('ration') }}],
                    backgroundColor: '#ff9800'
                },
                {
                    label: 'Peso total (g)',
                    data: [{{ $selectedSowings[0]->total_weight }}, {{ $selectedSowings[1]->total_weight }}],
                    backgroundColor: '#2196f3'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Comparación general entre siembras'
                },
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grace: '5%',
                    ticks: {
                        precision: 0,
                        stepSize: 1
                    },
                    title: {
                        display: true,
                        text: 'Valores'
                    }
                }
            }
        }
    });
</script>
@endif
@endsection
