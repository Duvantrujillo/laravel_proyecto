@extends('layouts.master')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Comparar Siembras Detalladamente</h1>

    @if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Errores de validación',
            html: `<ul style="text-align: left;">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>`
        });
    </script>
    @endif

    <form action="{{ route('sowing.compare') }}" method="GET" id="compareForm" class="mb-4 p-4 bg-white rounded-4 shadow-sm border border-2 border-primary-subtle">
        <h5 class="mb-3 text-primary fw-semibold">
            <i class="bi bi-bar-chart-fill me-2"></i>Comparación de Siembras
        </h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="sowingA" class="form-label fw-bold">Siembra A:</label>
                <select name="sowing_ids[]" id="sowingA" class="form-select shadow-sm">
                    <option disabled selected>Selecciona una siembra</option>
                    <option value="">Ninguna</option>
                    @foreach($sowings as $sowing)
                    <option value="{{ $sowing->id }}">
                        {{ $sowing->pond->name ?? 'Estanque desconocido' }} -
                        {{ $sowing->identifier->identificador ?? 'Sin identificador' }} |
                        Inicio: {{ \Carbon\Carbon::parse($sowing->sowing_date)->format('d/m/Y') }} |
                        Fin: {{ $sowing->sowing_completion ? \Carbon\Carbon::parse($sowing->sowing_completion)->format('d/m/Y') : 'En curso' }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="sowingB" class="form-label fw-bold">Siembra B:</label>
                <select name="sowing_ids[]" id="sowingB" class="form-select shadow-sm">
                    <option disabled selected>Selecciona otra siembra</option>
                    <option value="">Ninguna</option>
                    @foreach($sowings as $sowing)
                    <option value="{{ $sowing->id }}">
                        {{ $sowing->pond->name ?? 'Estanque desconocido' }} -
                        {{ $sowing->identifier->identificador ?? 'Sin identificador' }} |
                        Inicio: {{ \Carbon\Carbon::parse($sowing->sowing_date)->format('d/m/Y') }} |
                        Fin: {{ $sowing->sowing_completion ? \Carbon\Carbon::parse($sowing->sowing_completion)->format('d/m/Y') : 'En curso' }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-outline-primary px-4 py-2">
                <i class="bi bi-search me-1"></i>Comparar
            </button>
        </div>
    </form>

    @if(isset($selectedSowings) && count($selectedSowings) >= 1)
    <div class="row g-4 mb-4 justify-content-center">
        @foreach($selectedSowings as $sowing)
        @php
        $formatColombian = fn($v) => fmod($v, 1) === 0.0 ? number_format($v, 0, ',', '.') : number_format($v, 2, ',', '.');
        $totalRacion = $sowing->dietMonitorings->flatMap->feedRecords->sum('daily_ration');
        $totalKilos = $totalRacion / 1000;
        $bultoKg = 40;
        $totalBultos = $totalKilos / $bultoKg;
        @endphp
        <div class="{{ count($selectedSowings) === 1 ? 'col-md-8 offset-md-2' : 'col-md-6' }}">
            <div class="card h-100 shadow">
                <div class="card-header bg-success text-white fw-bold">
                    {{ $sowing->pond->name ?? 'Estanque desconocido' }} {{ $sowing->identifier->identificador ?? 'Sin identificador' }}<br>
                    {{ $sowing->sowing_date }} - {{ $sowing->sowing_completion ?? 'En curso' }}
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Fecha de siembra: <strong>{{ $sowing->sowing_date }}</strong></li>
                        <li class="list-group-item">Final de siembra: {{ $sowing->sowing_completion ?? 'N/A' }}</li>
                        <li class="list-group-item">Nombre especie: {{ $sowing->species->name ?? 'Tipo desconocido' }}</li>
                        <li class="list-group-item">Tipo de especie : {{ $sowing->type->name ?? 'Desconocido' }}</li>
                        <li class="list-group-item">Peces sembrados: {{ number_format($sowing->fish_count, 0, ',', '.') }}</li>
                        <li class="list-group-item">Peso total: {{ $formatColombian($sowing->total_weight) }} g</li>
                        <li class="list-group-item">Peso inicial: {{ $formatColombian($sowing->initial_weight) }} g</li>
                        <li class="list-group-item">Biomasa inicial: {{ $formatColombian($sowing->initial_biomass) }} g</li>
                        <li class="list-group-item">Densidad inicial: {{ $formatColombian($sowing->initial_density) }}</li>
                        <li class="list-group-item">Área: {{ $formatColombian($sowing->area) }} m²</li>
                        <li class="list-group-item">Origen: {{ $sowing->origin }}</li>
                        <li class="list-group-item">Frecuencia alimenticia: {{ $formatColombian($sowing->initial_feeding_frequency) }}</li>
                        <li class="list-group-item">Estado: {{ ucfirst($sowing->state) }}</li>

                        @if ($sowing->lastMonitoring)
                        <li class="list-group-item bg-info-subtle text-primary-emphasis border-start border-primary border-4 py-2 my-3">
                            <span class="fs-5 fw-bold text-center d-block">
                                🌾 Datos finales de la cosecha
                            </span>
                        </li>
                        <li class="list-group-item">Fecha del último muestreo: {{ \Carbon\Carbon::parse($sowing->lastMonitoring->sampling_date)->format('d/m/Y') }}</li>
                        <li class="list-group-item">Peso promedio final: {{ $formatColombian($sowing->lastMonitoring->average_weight) }} g</li>
                        <li class="list-group-item">Biomasa final: {{ $formatColombian($sowing->lastMonitoring->biomass) }} g</li>
                        <li class="list-group-item">Balance de peces final: {{ number_format($sowing->lastMonitoring->fish_balance, 0, ',', '.') }}</li>
                        <li class="list-group-item">Porcentaje de biomasa: {{ $formatColombian($sowing->lastMonitoring->biomass_percentage) }}%</li>
                        <li class="list-group-item">Alimento diario: {{ $formatColombian($sowing->lastMonitoring->daily_feed) }} g</li>
                        <li class="list-group-item">Número de raciones: {{ $formatColombian($sowing->lastMonitoring->ration_number) }}</li>
                        <li class="list-group-item">Ración total del día: {{ $formatColombian($sowing->lastMonitoring->ration) }} g</li>
                        <li class="list-group-item">Ganancia de peso: {{ $formatColombian($sowing->lastMonitoring->weight_gain) }} g</li>
                        <li class="list-group-item text-danger">Mortalidad acumulada: {{ $formatColombian($sowing->lastMonitoring->cumulative_mortality) }}%</li>
                        <li class="list-group-item">Tipo de alimento: {{ $sowing->lastMonitoring->feed_type ?? 'Sin especificar' }}</li>
                        <li class="list-group-item text-danger">Total de muertes: <strong>{{ number_format($sowing->mortalities->sum('amount'), 0, ',', '.') }}</strong></li>
                        <li class="list-group-item text-success">Total alimento suministrado: <strong>{{ $totalRacion > 0 ? $formatColombian($totalRacion) . ' g' : 'Sin registros' }}</strong></li>
                        @if ($totalRacion > 0)
                        <li class="list-group-item text-primary">Equivalente en kilogramos: <strong>{{ $formatColombian($totalKilos) }} kg</strong></li>
                        <li class="list-group-item text-warning">Bultos consumidos ({{ $bultoKg }} kg c/u): <strong>{{ $formatColombian($totalBultos) }}</strong></li>
                        @endif
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <h4 class="fw-bold text-center">Gráfica Comparativa Final</h4>
            <canvas id="sowingCharts" height="120"></canvas>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
@if(isset($selectedSowings) && count($selectedSowings) >= 1)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('sowingCharts').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                @foreach($selectedSowings as $sowing)
                '{{ $sowing->pond->name ?? 'Estanque' }} - {{ $sowing->identifier->identificador ?? 'Sin identificador' }}',
                @endforeach
            ],
            datasets: [{
                    label: 'Peces sembrados',
                    data: @json($selectedSowings->pluck('fish_count')),
                    backgroundColor: '#4caf50',
                    minBarLength: 5
                },
                {
                    label: 'Muertes totales',
                    data: @json($selectedSowings->map(fn($s) => $s->mortalities->sum('amount'))),
                    backgroundColor: '#f44336',
                    minBarLength: 5
                },
                {
                    label: 'Alimento total (g)',
                    data: @json($selectedSowings->map(fn($s) => $s->dietMonitorings->flatMap->feedRecords->sum('daily_ration'))),
                    backgroundColor: '#ff9800',
                    minBarLength: 5
                },
                {
                    label: 'Peso total (g)',
                    data: @json($selectedSowings->pluck('total_weight')),
                    backgroundColor: '#2196f3',
                    minBarLength: 5
                },
                {
                    label: 'Peso promedio final (g)',
                    data: @json($selectedSowings->map(fn($s) => $s->lastMonitoring->average_weight ?? 0)),
                    backgroundColor: '#9c27b0',
                    minBarLength: 5
                },
                {
                    label: 'Biomasa final (g)',
                    data: @json($selectedSowings->map(fn($s) => $s->lastMonitoring->biomass ?? 0)),
                    backgroundColor: '#00bcd4',
                    minBarLength: 5
                },
                {
                    label: 'Balance final de peces',
                    data: @json($selectedSowings->map(fn($s) => $s->lastMonitoring->fish_balance ?? 0)),
                    backgroundColor: '#795548',
                    minBarLength: 5
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
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed.y;
                            return `${context.dataset.label}: ${value.toLocaleString('es-CO')}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grace: '5%',
                    ticks: {
                        precision: 0
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

<script>
    document.getElementById('compareForm').addEventListener('submit', function(e) {
        const valA = document.getElementById('sowingA').value;
        const valB = document.getElementById('sowingB').value;
        if (valA && valB && valA === valB) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Selección inválida',
                text: 'No puedes comparar la misma siembra consigo misma.'
            });
        }
    });
</script>
@endsection
