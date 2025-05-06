@extends('layouts.master')

@section('title', 'Filtrar Entradas y Salidas')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-outline card-primary elevation-2" style="border-top: 3px solid #4b5e82;">
                    <div class="card-header bg-transparent border-0">
                        <h3 class="card-title text-dark"><i class="fas fa-clock mr-2"></i> Filtrar Entradas y Salidas</h3>
                    </div>
                    <div class="card-body p-4">
                        <!-- Formulario de filtro -->
                        <form method="GET" action="{{ route('entradas_salidas.filtradas') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="grupo_id" class="text-dark">Grupo</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light"><i class="fas fa-users"></i></span>
                                            </div>
                                            <select name="grupo_id" id="grupo_id" class="form-control">
                                                <option value="">Seleccione un grupo</option>
                                                @foreach($grupos as $grupo)
                                                    <option value="{{ $grupo->id }}" {{ request('grupo_id') == $grupo->id ? 'selected' : '' }}>{{ $grupo->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="ficha_id" class="text-dark">Ficha</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light"><i class="fas fa-id-badge"></i></span>
                                            </div>
                                            <select name="ficha_id" id="ficha_id" class="form-control">
                                                <option value="">Seleccione una ficha</option>
                                                @foreach($fichas as $ficha)
                                                    <option value="{{ $ficha->id }}" {{ request('ficha_id') == $ficha->id ? 'selected' : '' }}>{{ $ficha->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="fecha_hora_ingreso" class="text-dark">Fecha/Hora de Ingreso</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light"><i class="fas fa-calendar-alt"></i></span>
                                            </div>
                                            <input type="datetime-local" name="fecha_hora_ingreso" id="fecha_hora_ingreso" class="form-control" value="{{ request('fecha_hora_ingreso') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="fecha_hora_salida" class="text-dark">Fecha/Hora de Salida</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light"><i class="fas fa-calendar-times"></i></span>
                                            </div>
                                            <input type="datetime-local" name="fecha_hora_salida" id="fecha_hora_salida" class="form-control" value="{{ request('fecha_hora_salida') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-end">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="visito_ultimas_48h" class="text-dark">Visitó Granja (48h)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light"><i class="fas fa-tractor"></i></span>
                                            </div>
                                            <select name="visito_ultimas_48h" id="visito_ultimas_48h" class="form-control">
                                                <option value="">Todos</option>
                                                <option value="Sí" {{ request('visito_ultimas_48h') == 'Sí' ? 'selected' : '' }}>Sí</option>
                                                <option value="No" {{ request('visito_ultimas_48h') == 'No' ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="nombre" class="text-dark">Nombre Persona</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                            </div>
                                            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Buscar por nombre" value="{{ request('nombre') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="text-dark invisible">Filtrar</label> <!-- Label invisible para alineación -->
                                        <button type="submit" class="btn btn-primary btn-block" style="background: #4b5e82; border: none;"><i class="fas fa-search mr-1"></i> Filtrar</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Tabla de resultados -->
                        <div class="mt-4">
                            @if($registros->isEmpty())
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i> No hay entradas ni salidas registradas para los filtros seleccionados.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Persona</th>
                                                <th>Grupo</th>
                                                <th>Ficha</th>
                                                <th>Entrada</th>
                                                <th>Salida</th>
                                                <th>Visitó Granja (48h)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($registros as $registro)
                                                <tr>
                                                    <td>{{ $registro->nombreRelacion->nombre ?? 'Sin nombre' }}</td>
                                                    <td>{{ $registro->grupoRelacion->nombre ?? 'Sin grupo' }}</td>
                                                    <td>{{ $registro->fichaRelacion->nombre ?? 'Sin ficha' }}</td>
                                                    <td>{{ $registro->fecha_hora_ingreso->format('d/m/Y H:i:s') }}</td>
                                                    <td>{{ $registro->fecha_hora_salida ? $registro->fecha_hora_salida->format('d/m/Y H:i:s') : 'Pendiente' }}</td>
                                                    <td>{{ $registro->visito_ultimas_48h_texto }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Paginación ajustada -->
                                <div class="mt-3 d-flex justify-content-center">
                                    {{ $registros->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    /* Ajustes para la paginación */
    .pagination {
        font-size: 0.9rem;
    }
    .pagination .page-item .page-link {
        padding: 6px 12px;
        border-radius: 4px;
        color: #4b5e82;
        border: 1px solid #dcdcdc;
        transition: all 0.3s ease;
    }
    .pagination .page-item.active .page-link {
        background-color: #4b5e82;
        border-color: #4b5e82;
        color: white;
    }
    .pagination .page-item .page-link:hover {
        background-color: #e9ecef;
        border-color: #4b5e82;
    }
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
    }
    /* Asegurar que la tabla no desborde */
    .table-responsive {
        overflow-x: auto;
    }
    .table {
        margin-bottom: 0;
    }
    /* Ajuste para alinear el botón */
    .form-group {
        margin-bottom: 1rem; /* Mantener consistencia en el espaciado */
    }
</style>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.nav-sidebar a[href="{{ route('entradas_salidas.filtradas') }}"]').addClass('active').parents('.nav-item.has-treeview').addClass('menu-open');
    });




    $('#grupo_id').on('change', function () {
    var grupoId = $(this).val();
    if (grupoId) {
        $.ajax({
            url: '/obtener-fichas/' + grupoId,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('#ficha_id').empty();
                $('#ficha_id').append('<option value="">Seleccione una ficha</option>');
                $.each(data, function (key, ficha) {
                    $('#ficha_id').append('<option value="' + ficha.id + '">' + ficha.nombre + '</option>');
                });
            }
        });
    } else {
        $('#ficha_id').empty();
        $('#ficha_id').append('<option value="">Seleccione una ficha</option>');
    }
});

</script>
@endsection