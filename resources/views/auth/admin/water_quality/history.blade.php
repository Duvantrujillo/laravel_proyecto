@extends('layouts.master')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                backdrop: 'rgba(0,0,0,0.4)',
                customClass: {
                    confirmButton: 'btn btn-primary px-4'
                }
            });
        </script>
    @endif

    <div class="elegant-water-quality">
        <header class="elegant-header">
            <h1 class="elegant-title">Historial de Calidad de Agua</h1>
            <p class="elegant-subtitle">Registro de parámetros hídricos para la siembra</p>
        </header>

        @if ($waterQualities->isEmpty())
            <div class="elegant-message">
                No hay registros de calidad de agua para esta siembra.
            </div>
        @else
            <div class="elegant-table-wrapper">
                <table class="elegant-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>pH</th>
                            <th>Temperatura (°C)</th>
                            <th>Amonio (mg/L)</th>
                            <th>Turbidez (NTU)</th>
                            <th>Oxígeno Disuelto (mg/L)</th>
                            <th>Nitritos (mg/L)</th>
                            <th>Nitratos (mg/L)</th>
                            <th>Justificación</th>
                            <th>Responsable</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            function formatoCol($valor)
                            {
                                return fmod($valor, 1) === 0.0
                                    ? number_format($valor, 0, ',', '.')
                                    : number_format($valor, 2, ',', '.');
                            }
                        @endphp

                        @foreach ($waterQualities as $quality)
                            <tr>
                                <td>{{ $quality->date }}</td>
                                <td>{{ $quality->time }}</td>
                                <td>{{ formatoCol($quality->ph) }}</td>
                                <td>{{ formatoCol($quality->temperature) }}</td>
                                <td>{{ formatoCol($quality->ammonia) }}</td>
                                <td>{{ formatoCol($quality->turbidity) }}</td>
                                <td>{{ formatoCol($quality->dissolved_oxygen) }}</td>
                                <td>{{ formatoCol($quality->nitrites) }}</td>
                                <td>{{ formatoCol($quality->nitrates) }}</td>
                                <td>{{ $quality->justification ?? 'N/A' }}</td>
                                <td>
                                    {{ $quality->user ? $quality->user->name . ' ' . $quality->user->last_name : 'Sin usuario' }}
                                </td>

                                <td class="elegant-actions">
                                    <a href="{{ route('water_quality.edit', [$quality->id]) }}"
                                        class="elegant-button elegant-button--edit">Editar</a>
                                    @auth
                                        @if (Auth::user()->role === 'admin')
                                            <form action="{{ route('water_quality.destroy', [$quality->id]) }}" method="POST"
                                                class="elegant-form-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="elegant-button elegant-button--delete show-confirm"
                                                    data-id="{{ $quality->id }}">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    @endauth

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.show-confirm').forEach(button => {
                button.addEventListener('click', function() {
                    const form = this.closest('form');

                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: 'Esta acción no se puede deshacer.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, continuar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#e53e3e',
                        cancelButtonColor: '#4a5568',
                        backdrop: 'rgba(0,0,0,0.4)'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: '¿Confirmas la eliminación?',
                                text: 'El registro será eliminado permanentemente.',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonText: 'Sí, eliminar',
                                cancelButtonText: 'No, cancelar',
                                confirmButtonColor: '#e53e3e',
                                cancelButtonColor: '#4a5568',
                                backdrop: 'rgba(0,0,0,0.4)'
                            }).then((secondResult) => {
                                if (secondResult.isConfirmed) {
                                    form.submit();
                                }
                            });
                        }
                    });
                });
            });
        });
    </script>

    <style>
        .elegant-water-quality {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
            font-family: 'Inter', sans-serif;
            color: #1a202c;
        }

        .elegant-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .elegant-title {
            font-size: 32px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .elegant-subtitle {
            font-size: 16px;
            color: #6b7280;
            font-weight: 400;
        }

        .elegant-message {
            padding: 20px;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            text-align: center;
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 30px;
        }

        .elegant-table-wrapper {
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            background: #ffffff;
        }

        .elegant-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .elegant-table th,
        .elegant-table td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .elegant-table th {
            background: #f7fafc;
            color: #2d3748;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .elegant-table td {
            color: #1a202c;
        }

        .elegant-table tr:hover {
            background: #f7fafc;
        }

        .elegant-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .elegant-form-inline {
            display: inline-block;
        }

        .elegant-button {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }

        .elegant-button--edit {
            background: #2b6cb0;
            color: #ffffff;
        }

        .elegant-button--edit:hover {
            background: #2c5282;
            transform: translateY(-1px);
        }

        .elegant-button--delete {
            background: #e53e3e;
            color: #ffffff;
        }

        .elegant-button--delete:hover {
            background: #c53030;
            transform: translateY(-1px);
        }

        .elegant-button--back {
            background: #4a5568;
            color: #ffffff;
            display: inline-block;
            margin-top: 30px;
        }

        .elegant-button--back:hover {
            background: #2d3748;
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {

            .elegant-table th,
            .elegant-table td {
                padding: 12px;
            }

            .elegant-actions {
                flex-direction: column;
                gap: 8px;
            }

            .elegant-button {
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .elegant-water-quality {
                margin: 20px auto;
                padding: 0 16px;
            }

            .elegant-title {
                font-size: 28px;
            }

            .elegant-table {
                font-size: 12px;
            }
        }
    </style>
@endsection
