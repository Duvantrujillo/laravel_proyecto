@extends('layouts.master')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <title>Lista De Visitante</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
        <style>
            body {
                background-color: #f2f6fa;
            }

            .card {
                border-radius: 16px;
                box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
            }

            h2 {
                font-weight: bold;
                text-align: center;
            }

            label {
                font-weight: 600;
            }

            .form-control {
                border-radius: 8px;
            }

            .btn {
                border-radius: 8px;
            }

            .table th,
            .table td {
                vertical-align: middle;
            }

            .table-responsive {
                border-radius: 12px;
                overflow: hidden;
            }
        </style>
    </head>

    <body>
        <div class="container mt-5">
            <div class="card p-4">
                <h2 class="mb-4"><i class="bi bi-list-ul me-2"></i>Lista De Visitantes</h2>

                @if (session('success'))
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        Swal.fire({
                            title: '¡Éxito!',
                            text: '{{ session('success') }}',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                    </script>
                @endif

                @php
                    $estadoFormulario = \Illuminate\Support\Facades\Cache::get('formulario_publico_activo', true);
                @endphp

                <div class="mb-3 d-flex flex-wrap gap-2">
                    <a href="{{ route('visitors.create') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus-fill"></i> Registrar Nuevo Visitante
                    </a>

                    <a href="{{ route('visitors.checkout.form') }}" class="btn btn-warning">
                        <i class="bi bi-box-arrow-right"></i> Marcar Hora de Salida
                    </a>

                    <form method="POST" action="{{ route('visitors.toggle') }}">
                        @csrf
                        <input type="hidden" name="estado" value="{{ $estadoFormulario ? 'desactivar' : 'activar' }}">
                        <button type="submit" class="btn btn-{{ $estadoFormulario ? 'danger' : 'success' }}">
                            <i class="bi {{ $estadoFormulario ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                            {{ $estadoFormulario ? 'Desactivar' : 'Activar' }} Formulario Público
                        </button>
                    </form>

                    @if ($estadoFormulario)
                        <a href="{{ route('visitors.public.create') }}" target="_blank" class="btn btn-outline-success">
                            <i class="bi bi-link-45deg"></i> Ver Formulario Público
                        </a>
                    @endif
                </div>

                <form method="GET" action="{{ route('visitors.filter') }}" class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label><i class="bi bi-calendar-date"></i> Fecha</label>
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-info">
                            <i class="bi bi-funnel-fill"></i> Filtrar
                        </button>
                        <a href="{{ route('visitors.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Limpiar
                        </a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Documento</th>
                                <th>Teléfono</th>
                                <th>Fecha de Entrada</th>
                                <th>Hora de Entrada</th>
                                <th>Hora de Salida</th>
                                <th>Email</th>
                                <th>Procedencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($visitors as $v)
                                <tr>
                                    <td>{{ $v->name }}</td>
                                    <td>{{ $v->document }}</td>
                                    <td>{{ $v->phone }}</td>
                                    <td>{{ $v->entry_date }}</td>
                                    <td>{{ \Carbon\Carbon::parse($v->entry_time)->format('h:i A') }}</td>
                                    <td>{{ $v->exit_time ? \Carbon\Carbon::parse($v->exit_time)->format('h:i A') : 'Pendiente' }}
                                    </td>
                                    <td>{{ $v->email ?? '-' }}</td>
                                    <td>{{ $v->origin ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>

    </html>
@endsection
