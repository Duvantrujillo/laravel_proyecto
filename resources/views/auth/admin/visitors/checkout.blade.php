@extends('layouts.master')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Marcar Hora de Salida</title>
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

        .btn-primary {
            background-color: #0d6efd;
            border: none;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: #084cdf;
        }

        .btn-secondary {
            border-radius: 8px;
        }

        .icon-label {
            display: flex;
            align-items: center;
            gap: 6px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card p-4">
                <h2 class="mb-4">Marcar Hora de Salida</h2>

                <form method="POST" action="{{ route('visitors.checkout.update') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="icon-label"><i class="bi bi-clock-history"></i>Hora de Salida</label>
                        <input type="time" name="exit_time" class="form-control" required>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px;"><input type="checkbox" id="checkAll"></th>
                                    <th>Nombre</th>
                                    <th>Fecha de Entrada</th>
                                    <th>Hora de Entrada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($visitors as $v)
                                <tr>
                                    <td><input type="checkbox" name="visitor_ids[]" value="{{ $v->id }}"></td>
                                    <td>{{ $v->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($v->entry_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($v->entry_time)->format('h:i A') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle"></i> Actualizar Salida
                        </button>
                        <a href="{{ route('visitors.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left-circle"></i> Volver
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('checkAll').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('input[type="checkbox"][name="visitor_ids[]"]');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
</body>
</html>

@endsection