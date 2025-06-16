@extends('layouts.master')

@section('content')
<!-- CDN de Font Awesome para íconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-K5qVN3+9OqYq+5ZPQax6FzU1xPUSjqMZxBu0wFlVbJ6KHgUt7Z7Z8uZTk0SKx0FmqMi7q1L8aCJ7NQxj8FYw2Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    .sowing-container {
        padding: 20px;
        max-width: 100%;
    }

    .accordion-item {
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 15px;
        overflow: hidden;
    }

    .accordion-header {
        background-color: #f7f7f7;
        cursor: pointer;
        padding: 12px 20px;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .accordion-header:hover {
        background-color: #eaeaea;
    }

    .accordion-body {
        display: none;
        padding: 15px 20px;
        background-color: #fff;
    }

    .sowing-info {
        margin-bottom: 15px;
        font-size: 14px;
    }

    .sowing-info span {
        display: inline-block;
        width: 220px;
        font-weight: bold;
    }

    .action-group {
        margin-bottom: 15px;
    }

    .group-title {
        font-weight: bold;
        margin-bottom: 5px;
        color: #555;
        border-bottom: 1px solid #ddd;
        padding-bottom: 3px;
    }

    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .btn-action {
        padding: 6px 12px;
        font-size: 13px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        flex: 1 1 auto;
        max-width: 250px;
        color: white;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-follow-up {
        background-color: #4CAF50;
    }

    .btn-follow-up2 {
        background-color: #1c38a6;
    }

    .btn-end-follow-up {
        background-color: #f44336;
    }

    .btn-disabled {
        background-color: #ffeb3b;
        color: black;
        cursor: not-allowed;
    }

    .btn-water-history {
        background-color: #ff9800;
    }

    .status-message {
        font-size: 12px;
        color: #d84315;
        margin-top: 4px;
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
    }

    .rotate {
        transform: rotate(90deg);
        transition: transform 0.3s ease;
    }

    .rotate.down {
        transform: rotate(0deg);
    }

    .accordion-title {
        font-weight: bold;
        font-size: 15px;
    }

    .accordion-subtitle {
        font-size: 13px;
        color: #555;
    }
</style>

<div class="sowing-container">
    <h2>Siembras con estado 'inicializada'</h2>

    @php use App\Models\Mortality; @endphp

    @foreach ($sowings as $sowing)
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
    $canRegisterFollowUp = $isFirst || $mortalityCount >= 15;
    $canRegisterFeedRecord = !$isFirst;

    function formatNumber($value) {
    if (is_null($value)) return 'No disponible';
    return fmod($value, 1) == 0
    ? number_format($value, 0, ',', '.')
    : number_format($value, 2, ',', '.');
    }

    $monitoringCount = $sowing->dietMonitorings->count();
    $feedRecordsCount = $lastMonitoring ? $lastMonitoring->feedRecords()->count() : 0;
    $registroAlimentacionHabilitado = $monitoringCount >= 1 && $feedRecordsCount < 15;
        @endphp

        <div class="accordion-item">
        <div class="accordion-header">
            <div>
                <div class="accordion-title">Siembra del {{ $sowing->sowing_date }}</div>
                <div class="accordion-subtitle">
                    {{ $sowing->pond->name ?? 'No disponible' }} {{ $sowing->identifier->identificador ?? 'No disponible' }}
                </div>
            </div>
            <span class="arrow">&#9656;</span>
        </div>
        <div class="accordion-body">
            {{-- Información --}}
            <div class="sowing-info"><span>Estanque:</span> {{ $sowing->pond->name ?? 'No disponible' }}</div>
            <div class="sowing-info"><span>Identificador:</span> {{ $sowing->identifier->identificador ?? 'No disponible' }}</div>
            <div class="sowing-info"><span>Biomasa Inicial:</span> {{ formatNumber($sowing->initial_biomass) }}</div>
            <div class="sowing-info"><span>Especie:</span> {{ $sowing->type->species->name ?? 'No disponible' }}</div>
            <div class="sowing-info"><span>Tipo:</span> {{ $sowing->type->name ?? 'No disponible' }}</div>
            <div class="sowing-info"><span>Frecuencia de alimentación:</span> {{ $sowing->initial_feeding_frequency }}</div>
            <div class="sowing-info"><span>Número de Peces:</span> {{ formatNumber($sowing->fish_count) }}</div>
            <div class="sowing-info"><span>Origen:</span> {{ $sowing->origin }}</div>
            <div class="sowing-info"><span>Área:</span> {{ formatNumber($sowing->area) }}</div>
            <div class="sowing-info"><span>Peso Inicial:</span> {{ formatNumber($sowing->initial_weight) }}</div>
            <div class="sowing-info"><span>Peso Total:</span> {{ formatNumber($sowing->total_weight) }}</div>
            <div class="sowing-info"><span>Densidad Inicial:</span> {{ formatNumber($sowing->initial_density) }}</div>
            <div class="sowing-info"><span>Estado:</span> {{ $sowing->state }}</div>

            {{-- Seguimiento --}}
            <div class="action-group">
                <div class="group-title">Seguimiento</div>
                <div class="action-buttons" style="flex-direction: column; align-items: flex-start;">
                    @if ($canRegisterFollowUp)
                    <a href="{{ route('diet_monitoring.index', ['sowing_id' => $sowing->id]) }}" class="btn-action btn-follow-up">
                        <i class="fas fa-stethoscope"></i> Hacer seguimiento
                    </a>
                    @else
                    <button class="btn-action btn-disabled" disabled>
                        <i class="fas fa-stethoscope"></i> Hacer seguimiento
                    </button>
                    <div class="status-message">
                        Faltan {{ 15 - $mortalityCount }} registros de mortalidad
                    </div>
                    @endif

                    <a href="{{ route('sowing.diet_monitoring', ['sowing' => $sowing->id]) }}" class="btn-action btn-follow-up2" style="margin-top: 8px;">
                        <i class="fas fa-chart-line"></i> Ver seguimiento
                    </a>
                </div>
            </div>

            {{-- Calidad del Agua --}}
            <div class="action-group">
                <div class="group-title">Calidad del Agua</div>
                <div class="action-buttons">
                    <a href="{{ route('water_quality.create', $sowing->id) }}" class="btn-action btn-follow-up">
                        <i class="fas fa-tint"></i> Registrar Calidad
                    </a>
                    <a href="{{ route('water_quality.history', ['sowing' => $sowing->id]) }}" class="btn-action btn-follow-up2">
                        <i class="fas fa-history"></i> Historial Calidad
                    </a>
                </div>
            </div>

            {{-- Alimentación --}}
            <div class="action-group">
                <div class="group-title">Alimentación</div>
                <div class="action-buttons">
                    @if ($registroAlimentacionHabilitado)
                    <a href="{{ route('feed_records.create', ['sowingId' => $sowing->id]) }}" class="btn-action btn-follow-up">
                        <i class="fas fa-utensils"></i> Registro de Alimentación
                    </a>
                    @else
                    <button class="btn-action btn-disabled" disabled>
                        <i class="fas fa-utensils"></i> Registro de Alimentación
                    </button>
                    @endif
                    <a href="{{ route('feed_records.history', ['sowingId' => $sowing->id]) }}" class="btn-action btn-water-history">
                        <i class="fas fa-book"></i> Ver Historial Alimentación
                    </a>
                </div>
            </div>

            {{-- Finalizar --}}
            @auth
            @if (Auth::user()->role === 'admin')
            <div class="action-group">
                <div class="group-title">Finalizar</div>
                <div class="action-buttons">
                    <form id="finish-form-{{ $sowing->id }}" action="{{ route('sowing.finish', $sowing->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="button" class="btn-action btn-end-follow-up finish-btn" data-id="{{ $sowing->id }}">
                            <i class="fas fa-check-circle"></i> Terminar seguimiento
                        </button>
                    </form>
                </div>
            </div>
            @endif
            @endauth

        </div>
</div>
@endforeach
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Confirmar finalizar seguimiento
    document.querySelectorAll('.finish-btn').forEach(button => {
        button.addEventListener('click', function() {
            const sowingId = this.getAttribute('data-id');
            Swal.fire({
                title: '¿Quieres terminar el seguimiento?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Confirmar',
                        text: '¿Seguro que quieres terminar el seguimiento?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar',
                    }).then((confirmResult) => {
                        if (confirmResult.isConfirmed) {
                            document.getElementById('finish-form-' + sowingId).submit();
                        }
                    });
                }
            });
        });
    });

    // Acordeón
    document.querySelectorAll('.accordion-header').forEach(header => {
        header.addEventListener('click', () => {
            const body = header.nextElementSibling;
            const arrow = header.querySelector('.arrow');
            body.style.display = (body.style.display === 'block') ? 'none' : 'block';
            arrow.classList.toggle('down');
        });
    });
</script>
@endsection