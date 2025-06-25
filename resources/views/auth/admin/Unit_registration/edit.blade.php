@extends('layouts.master')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Registro de Mortalidad</h1>

    <form id="formEditar" action="{{ route('mortality.update', $mortalidad->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="datetime" class="form-label">Fecha y Hora</label>
            <input type="datetime-local" name="datetime" id="datetime" class="form-control"
                value="{{ \Carbon\Carbon::parse($mortalidad->datetime)->format('Y-m-d\TH:i') }}" required>
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Cantidad de Mortalidad</label>
            <input type="number" name="amount" id="amount" class="form-control"
                value="{{ $mortalidad->amount }}" min="0" required>
        </div>

        <div class="mb-3">
            <label for="observation" class="form-label">Observación</label>
            <textarea name="observation" id="observation" class="form-control" rows="4">{{ $mortalidad->observation }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Código del Estanque</label>
            <input type="text" class="form-control" disabled
                value="{{ $mortalidad->pondUnitCode->pond->name ?? 'Sin nombre' }} - {{ $mortalidad->pondUnitCode->identificador ?? 'Sin identificador' }}">
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('mortality.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection

@section('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('formEditar').addEventListener('submit', function (e) {
        e.preventDefault(); // Previene el envío inmediato

        Swal.fire({
            title: '¿Deseas guardar los cambios?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'Cancelar'
        }).then((resultado) => {
            if (resultado.isConfirmed) {
                const form = e.target;

                // Enviar con fetch para evitar recargar y manejar respuesta
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: new FormData(form)
                }).then(response => {
                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Actualizado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = "{{ route('mortality.index') }}";
                        });
                    } else {
                        response.json().then(data => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al actualizar',
                                text: data.message || 'Ocurrió un error inesperado.'
                            });
                        });
                    }
                }).catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo contactar al servidor.'
                    });
                });
            }
        });
    });
</script>
@endsection
