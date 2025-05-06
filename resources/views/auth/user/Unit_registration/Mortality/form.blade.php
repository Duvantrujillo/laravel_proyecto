@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registrar Mortalidad</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('mortality.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="lake">Seleccionar Lago:</label>
            <select name="pond_id" id="pond_id" class="form-control">
                <option value="">Seleccione un lago</option>
                @foreach ($lakes as $lake)
                    <option value="{{ $lake->id }}">{{ $lake->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mt-3">
            <label for="identificador">Seleccionar Estanque:</label>
            <select name="pond_code_id" id="identificador" class="form-control">
                <option value="">Seleccione un identificador</option>
            </select>
        </div>

        <div class="form-group mt-3">
            <label for="datetime">Fecha y Hora:</label>
            <input type="datetime-local" name="datetime" class="form-control" required>
        </div>

        <div class="form-group mt-3">
            <label for="amount">Cantidad de Mortalidad:</label>
            <input type="number" name="amount" class="form-control" required>
        </div>

        <div class="form-group mt-3">
            <label for="fish_balance">Balance de Peces:</label>
            <input type="number" name="fish_balance" class="form-control" required>
        </div>

        <div class="form-group mt-3">
            <label for="observation">Observaciones:</label>
            <textarea name="observation" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Guardar</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#pond_id').change(function() {
        var pondId = $(this).val();

        if (pondId) {
            $.ajax({
                url: '{{ route("mortality.getPondsByPondId") }}',
                method: 'GET',
                data: { pond_id: pondId },
                success: function(response) {
                    $('#identificador').empty();
                    $('#identificador').append('<option value="">Seleccione un identificador</option>');

                    $.each(response, function(index, pondUnit) {
                        $('#identificador').append('<option value="' + pondUnit.id + '">' + pondUnit.identificador + '</option>');
                    });
                }
            });
        } else {
            $('#identificador').empty();
            $('#identificador').append('<option value="">Seleccione un identificador</option>');
        }
    });
});
</script>
@endsection
