@extends('layouts.master')

@section('content')

@php
    // Definición temporal en caso de que el helper no esté cargado
    if (!function_exists('formatNumberCol')) {
        function formatNumberCol($number) {
            if ($number === null) return '-';

            if (floor($number) == $number) {
                return number_format($number, 0, ',', '.');
            }

            if (round($number, 1) == $number) {
                return number_format($number, 1, ',', '.');
            }

            return number_format($number, 2, ',', '.');
        }
    }
@endphp

<div class="container mt-4">
    <div class="card border-light shadow-sm">
        <div class="card-header bg-light-teal border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 text-dark-teal">
                <i class="fas fa-seedling me-2"></i>Seguimiento de siembra - {{ $sowing->sowing_date }}
            </h5>
            <a href="{{ url()->previous() }}" class="btn btn-outline-dark-teal btn-sm">
                <i class="fas fa-chevron-left me-2"></i>Regresar
            </a>
        </div>

        <div class="card-body p-0">
            @if($monitorings->isEmpty())
                <div class="alert alert-light-teal text-center m-4">
                    <i class="fas fa-database me-2"></i>No hay registros de seguimiento
                </div>
            @else
                <div class="table-responsive compact-table">
                    <table class="table table-sm table-borderless align-middle mb-0">
                        <thead class="bg-soft-teal">
                            <tr>
                                <th class="text-start ps-4">Muestreo</th>
                                <th class="text-end">Peso (g)</th>
                                <th class="text-end">Balance</th>
                                <th class="text-end">% Biom.</th>
                                <th class="text-end">Biomasa</th>
                                <th class="text-end">Alimento (g)</th>
                                <th class="text-end">Raciones</th>
                                <th class="text-end">Ración (g)</th>
                                <th class="text-end">Ganancia</th>
                                <th class="text-end">Mortalidad</th>
                                <th class="text-end pe-4">Alimento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($monitorings as $monitoring)
                            <tr class="hover-row">
                                <td class="text-muted ps-4">{{ \Carbon\Carbon::parse($monitoring->sampling_date)->format('d/m/y') }}</td>
                                <td class="text-end">{{ formatNumberCol($monitoring->average_weight) }}</td>
                                <td class="text-end">{{ formatNumberCol($monitoring->fish_balance) }}</td>
                                <td class="text-end">{{ formatNumberCol($monitoring->biomass_percentage) }}</td>
                                <td class="text-end">{{ formatNumberCol($monitoring->biomass) }}</td>
                                <td class="text-end">{{ formatNumberCol($monitoring->daily_feed) }}</td>
                                <td class="text-end">{{ formatNumberCol($monitoring->ration_number) }}</td>
                                <td class="text-end">{{ formatNumberCol($monitoring->ration) }}</td>
                                <td class="text-end text-success">{{ formatNumberCol($monitoring->weight_gain) }}</td>
                                <td class="text-end">
                                    <span class="mortality-indicator {{ $monitoring->cumulative_mortality > 5 ? 'high' : 'low' }}">
                                        {{ formatNumberCol($monitoring->cumulative_mortality) }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <span class="badge bg-soft-teal text-dark-teal">{{ $monitoring->feed_type }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    :root {
        --light-teal: #e8f4f8;
        --dark-teal: #2a5d68;
        --soft-teal: #f0f8fa;
    }

    .bg-light-teal { background-color: var(--light-teal); }
    .bg-soft-teal { background-color: var(--soft-teal); }
    .text-dark-teal { color: var(--dark-teal); }
    .btn-outline-dark-teal {
        border-color: var(--dark-teal);
        color: var(--dark-teal);
    }

    .compact-table th { 
        font-weight: 500;
        font-size: 0.9rem;
        padding: 0.75rem;
    }

    .compact-table td {
        padding: 0.6rem;
        font-size: 0.9rem;
    }

    .hover-row:hover {
        background-color: #f8fafb;
    }

    .mortality-indicator {
        padding: 2px 8px;
        border-radius: 12px;
    }

    .mortality-indicator.low {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .mortality-indicator.high {
        background-color: #ffebee;
        color: #c62828;
    }

    .badge.bg-soft-teal {
        padding: 0.35em 0.65em;
        font-weight: 400;
    }
</style>
@endsection
