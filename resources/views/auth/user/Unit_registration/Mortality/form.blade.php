@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registrar Mortalidad</h2>

    <form id="mortality-form" action="{{ route('mortality.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="lake">Seleccionar Lago:</label>
            <select name="pond_id" id="pond_id" class="form-control">
                <option value="">Seleccione un lago</option>
                @foreach ($lakes as $lake)
                    <option value="{{ $lake->id }}" {{ request('pond_id') == $lake->id ? 'selected' : '' }}>{{ $lake->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mt-3">
            <label for="identificador">Seleccionar Estanque:</label>
            <select name="pond_code_id" id="identificador" class="form-control">
                <option value="">Seleccione un identificador</option>
                @if(request('pond_code_id'))
                    @php
                        $selectedPond = \App\Models\pond_unit_code::find(request('pond_code_id'));
                    @endphp
                    @if($selectedPond)
                        <option value="{{ $selectedPond->id }}" selected>{{ $selectedPond->identificador }}</option>
                    @endif
                @endif
            </select>
        </div>

        <div class="form-group mt-3">
            <label for="datetime">Fecha y Hora:</label>
            <input type="datetime-local" name="datetime" class="form-control" required value="{{ old('datetime', now()->format('Y-m-d\TH:i')) }}">
        </div>

        <div class="form-group mt-3">
            <label for="amount">Cantidad de Mortalidad:</label>
            <input type="number" name="amount" class="form-control" min="0" required value="{{ old('amount') }}">
        </div>

        <div class="form-group mt-3">
            <label for="fish_balance">Balance de Peces:</label>
            <input type="number" name="fish_balance" id="fish_balance" class="form-control" readonly required value="{{ old('fish_balance') }}">
        </div>

        <div class="form-group mt-3">
            <label for="observation">Observaciones:</label>
            <textarea name="observation" class="form-control">{{ old('observation') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-3" id="submit-btn">Guardar</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Preseleccionar estanque si viene en la URL
    @if(request('pond_code_id'))
        checkMortalityLimit({{ request('pond_code_id') }});
    @endif

    $('#pond_id').change(function() {
        var pondId = $(this).val();
        $('#identificador').empty().append('<option value="">Seleccione un identificador</option>');
        $('#fish_balance').val('');
        enableSubmit(true);

        if (pondId) {
            $.ajax({
                url: '{{ route("mortality.getPondsByPondId") }}',
                method: 'GET',
                data: { pond_id: pondId },
                success: function(response) {
                    $.each(response, function(index, pondUnit) {
                        $('#identificador').append('<option value="' + pondUnit.id + '">' + pondUnit.identificador + '</option>');
                    });
                }
            });
        }
    });

    $('#identificador').change(function() {
        const pondCodeId = $(this).val();
        if (pondCodeId) {
            checkMortalityLimit(pondCodeId);
            getSowingData(pondCodeId);
        } else {
            $('#fish_balance').val('');
            enableSubmit(true);
        }
    });

    function enableSubmit(enable) {
        $('#submit-btn').prop('disabled', !enable);
    }

    function checkMortalityLimit(pondCodeId) {
        $.ajax({
            url: '{{ route("mortality.getSowingData") }}',
            method: 'GET',
            data: { pond_code_id: pondCodeId, check_limit: true },
            success: function(data) {
                if (data.registros_realizados !== undefined) {
                    let message = `Registros realizados: ${data.registros_realizados}/15`;
                    if (data.registros_realizados >= 15) {
                        if (data.seguimiento_reciente) {
                            message += " - Seguimiento quincenal reciente detectado, puede registrar.";
                            Swal.fire({
                                icon: 'success',
                                title: '¡Puede Registrar!',
                                text: message,
                                confirmButtonText: 'Entendido'
                            });
                            enableSubmit(true);
                        } else {
                            message += " - Límite alcanzado, realice seguimiento quincenal.";
                            Swal.fire({
                                icon: 'warning',
                                title: '¡Atención!',
                                text: message,
                                confirmButtonText: 'Entendido'
                            });
                            enableSubmit(false);
                        }
                    } else {
                        enableSubmit(true);
                    }
                }
            }
        });
    }

    function getSowingData(pondCodeId) {
        $.ajax({
            url: '{{ route("mortality.getSowingData") }}',
            method: 'GET',
            data: { pond_code_id: pondCodeId },
            success: function(data) {
                $('#fish_balance').val(data.fish_balance);
            },
            error: function(xhr) {
                const mensaje = xhr.responseJSON?.error || 'Error al obtener los datos de siembra.';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: mensaje,
                    confirmButtonText: 'Entendido'
                });
                $('#fish_balance').val('');
                enableSubmit(false);
            }
        });
    }

    // Mostrar alertas de Laravel session con SweetAlert2
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: @json(session('error')),
            confirmButtonText: 'Entendido'
        });
        enableSubmit(false);
    @endif

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: @json(session('success')),
            confirmButtonText: 'Perfecto'
        });
    @endif
});
</script>
@endsection
