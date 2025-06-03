@extends('layouts.master')

@section('content')

@if ($errors->any())
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: '¡Error!',
            html: '<ul>' +
                @foreach ($errors->all() as $error)
                    '<li>{{ $error }}</li>' +
                @endforeach
            '</ul>',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif


<div class="container">
    <h1>Register De Encabezado De Dieta</h1>


    <form action="{{ route('siembras.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Fecha De Siembra</label>
            <input type="date" name="sowing_date" class="form-control" required>
        </div>

        <div class="form-group">
            <label>%Biomasa Inicial</label>
            <input type="number" step="0.01" name="initial_biomass" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Selecciona Especie</label>
            <select id="especie" name="species_id" class="form-control" required>
                <option value="">Select species</option>
                @foreach ($species as $especie)
                    <option value="{{ $especie->id }}">{{ $especie->species_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Tipo De Especie</label>
            <select id="tipo" name="type_id" class="form-control" required>
                <option value="">Seleccione Tipo</option>
            </select>
        </div>

        <div class="form-group">
            <label>Frecuencia De Alimentacion Inicial</label>
            <input type="number" name="initial_feeding_frequency" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Numero De Peces</label>
            <input type="number" id="numero_peces" name="fish_count" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Origen</label>
            <input type="text" name="origin" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Area</label>
            <input type="number" step="0.01" id="area" name="area" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Peso Inicial</label>
            <input type="number" step="0.01" id="peso_inicial" name="initial_weight" class="form-control" required>
        </div>

        <!-- Campo visible formateado -->
        <div class="form-group">
            <label>Peso Total (kg)</label>
            <input type="text" id="peso_total_visible" class="form-control" readonly>
        </div>

        <!-- Campo oculto con valor real para enviar -->
        <input type="hidden" id="peso_total" name="total_weight">

        <div class="form-group">
            <label>Densidad Inicial</label>
            <input type="number" step="0.01" id="densidad_inicial" name="initial_density" class="form-control" readonly>
        </div>

        <div class="form-group">
            <label>Estanque</label>
            <select id="estanque" name="pond_id" class="form-control" required>
                <option value="">Seleccione Estanque</option>
                @foreach ($ponds as $estanque)
                    <option value="{{ $estanque->pond_id }}">{{ $estanque->pond_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Seleccione Identificador Del Estanque</label>
            <select id="identificador" name="identifier_id" class="form-control" required>
                <option value="">Seleccione identificador</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Register Encabezado de Dieta</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Cargar tipos al seleccionar especie
    $('#especie').change(function () {
        var especieId = $(this).val();
        if (especieId) {
            $.get('/get-tipos/' + especieId, function (data) {
                $('#tipo').empty().append('<option value="">Select type</option>');
                $.each(data, function (key, value) {
                    $('#tipo').append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            });
        }
    });

    // Cargar identificadores al seleccionar estanque
    $('#estanque').change(function () {
        var pondId = $(this).val();
        if (pondId) {
            $.get('/get-identificadores/' + pondId, function (data) {
                $('#identificador').empty().append('<option value="">Select identifier</option>');
                $.each(data, function (key, value) {
                    $('#identificador').append('<option value="' + value.id + '">' + value.identificador + '</option>');
                });
            });
        } else {
            $('#identificador').empty().append('<option value="">Select identifier</option>');
        }
    });

    // Calcular peso total y densidad inicial con formato
    $('#numero_peces, #peso_inicial, #area').on('input', function () {
        var numeroPeces = parseFloat($('#numero_peces').val()) || 0;
        var pesoInicial = parseFloat($('#peso_inicial').val()) || 0;
        var area = parseFloat($('#area').val()) || 0;

        var pesoTotal = Math.floor(numeroPeces * pesoInicial);
        $('#peso_total').val(pesoTotal); // valor real (sin puntos, sin decimales)
        $('#peso_total_visible').val(pesoTotal.toLocaleString('es-CO')); // valor visible formateado

        if (area > 0) {
            $('#densidad_inicial').val((numeroPeces / area).toFixed(2));
        } else {
            $('#densidad_inicial').val(0);
        }
    });
</script>

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

@endsection
