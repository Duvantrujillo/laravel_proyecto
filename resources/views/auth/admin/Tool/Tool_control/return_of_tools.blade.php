@extends('layouts.master')

@section('content')
    <div class="container">
        <h2>Registrar Devolución de Herramienta</h2>

        <form action="{{ route('returns.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Selección de préstamo con información detallada incluyendo cédula -->
            <div class="form-group">
                <label>Seleccionar Préstamo</label>
                <select name="loan_id" class="form-control" required>
                    <option value="">-- Selecciona un préstamo --</option>
                    @foreach ($loans as $loan)
                        @php
                            $pending = $loan->quantity - $loan->returned_quantity;
                        @endphp
                        @if ($pending > 0)
                            <option value="{{ $loan->id }}">
                                {{ $loan->item }} | Solicitado por: {{ $loan->requester_name }} | Cédula:
                                {{ $loan->requester_id }} | Fecha:
                                {{ \Carbon\Carbon::parse($loan->loan_date)->format('Y-m-d') }} | Prestado:
                                {{ $loan->quantity }}, Devuelto: {{ $loan->returned_quantity }}, Pendiente:
                                {{ $pending }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- Cantidad devuelta -->
            <div class="form-group">
                <label>Cantidad Devuelta</label>
                <input type="number" name="returned_quantity" class="form-control" min="1" required
                    value="{{ old('returned_quantity') }}">
            </div>

            <!-- Fecha de devolución -->
            <div class="form-group">
                <label>Fecha de Devolución</label>
                <input type="datetime-local" name="return_date" class="form-control" required
                    value="{{ old('return_date', now()->format('Y-m-d\TH:i')) }}" min="{{ now()->format('Y-m-d\TH:i') }}">
            </div>


            <!-- Estado o condición en que se devolvió -->
            <div class="form-group">
                <label>Estado / Descripción de la Devolución</label>
                <textarea name="return_status" class="form-control" rows="3">{{ old('return_status') }}</textarea>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#modal-dialog modal-dialog-centered">
                    Launch demo modal
                </button>

                <label for="">Evidencia</label>
                <input type="file" name="img" class="form-control" accept="image/*">
            </div>
            <!-- Oculto: Recibido por -->
            <input type="hidden" name="received_by" value="{{ auth()->user()->name }}">
            <button type="submit" class="btn btn-success">Guardar Devolución</button>
        </form>
    </div>
    <div class="modal-dialog modal-dialog-centered">
        ...
    </div>


    @if ($errors->has('returned_quantity'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: 'Error',
                text: '{{ $errors->first('returned_quantity') }}',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
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
    @if ($errors->any())
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: 'Error de validación',
                text: '{{ $errors->first() }}',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
@endsection
