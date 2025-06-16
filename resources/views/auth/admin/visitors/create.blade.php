@extends('layouts.master')

@section('content')



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Registro De Visitante</title>
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
        <div class="col-md-8">
            <div class="card p-4">
                <h2 class="mb-4">Registro De Visitante</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('visitors.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="icon-label"><i class="bi bi-person-fill"></i>Nombre</label>
                        <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                    </div>

                    <div class="mb-3">
                        <label class="icon-label"><i class="bi bi-card-text"></i>Documento</label>
                        <input type="text" name="document" class="form-control" required value="{{ old('document') }}">
                    </div>

                    <div class="mb-3">
                        <label class="icon-label"><i class="bi bi-telephone-fill"></i>Teléfono</label>
                        <input type="text" name="phone" class="form-control" required value="{{ old('phone') }}">
                    </div>

                    <div class="mb-3">
                        <label class="icon-label"><i class="bi bi-calendar-date-fill"></i>Fecha De Entrada</label>
                        <input type="date" name="entry_date" class="form-control" required value="{{ old('entry_date') }}">
                    </div>

                    <div class="mb-3">
                        <label class="icon-label"><i class="bi bi-clock-fill"></i>Hora De Entrada</label>
                        <input type="time" name="entry_time" class="form-control" required value="{{ old('entry_time') }}">
                    </div>

                    <div class="mb-3">
                        <label class="icon-label"><i class="bi bi-envelope-fill"></i>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="mb-3">
                        <label class="icon-label"><i class="bi bi-geo-alt-fill"></i>Procedencia</label>
                        <input type="text" name="origin" class="form-control" value="{{ old('origin') }}">
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar
                        </button>
                        <a href="{{ route('visitors.index') }}" class="btn btn-secondary">
                            <i class="bi bi-list-ul"></i> Lista De Registro
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
</body>
</html>

@endsection