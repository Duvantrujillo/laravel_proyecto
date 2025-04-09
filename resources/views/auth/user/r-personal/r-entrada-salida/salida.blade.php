@extends('layouts.master')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card card-outline card-primary elevation-2" style="border-top: 3px solid #4b5e82;">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <h3 class="card-title text-dark"><i class="fas fa-door-open mr-2"></i> Registros de Salida</h3>
                    </div>
                    <div class="card-body p-4">
                        <!-- Formulario de filtrado -->
                        <form action="{{ route('entrada_salida.index') }}" method="GET" class="mb-4">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="nombre" class="d-block text-dark">Nombre</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                            </div>
                                            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre" value="{{ $filtros['nombre'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="fecha_hora_ingreso" class="d-block text-dark">Fecha de Ingreso</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light"><i class="fas fa-calendar-alt"></i></span>
                                            </div>
                                            <input type="date" name="fecha_hora_ingreso" id="fecha_hora_ingreso" class="form-control" value="{{ $filtros['fecha_hora_ingreso'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="fecha_hora_salida" class="d-block text-dark">Fecha de Salida</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light"><i class="fas fa-calendar-times"></i></span>
                                            </div>
                                            <input type="date" name="fecha_hora_salida" id="fecha_hora_salida" class="form-control" value="{{ $filtros['fecha_hora_salida'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="visito_ultimas_48h" class="d-block text-dark">Visitó Últimas 48h</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light"><i class="fas fa-tractor"></i></span>
                                            </div>
                                            <select name="visito_ultimas_48h" id="visito_ultimas_48h" class="form-control">
                                                <option value="">Todos</option>
                                                <option value="Sí" {{ isset($filtros['visito_ultimas_48h']) && $filtros['visito_ultimas_48h'] == 'Sí' ? 'selected' : '' }}>Sí</option>
                                                <option value="No" {{ isset($filtros['visito_ultimas_48h']) && $filtros['visito_ultimas_48h'] == 'No' ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="grupo" class="d-block text-dark">Grupo</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light"><i class="fas fa-users"></i></span>
                                            </div>
                                            <select name="grupo" id="grupo" class="form-control">
                                                <option value="">Todos</option>
                                                @foreach($grupos as $grupo)
                                                    <option value="{{ $grupo->id }}" {{ isset($filtros['grupo']) && $filtros['grupo'] == $grupo->id ? 'selected' : '' }}>{{ $grupo->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="ficha" class="d-block text-dark">Ficha</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light"><i class="fas fa-id-badge"></i></span>
                                            </div>
                                            <select name="ficha" id="ficha" class="form-control">
                                                <option value="">Todas</option>
                                                @foreach($fichas as $ficha)
                                                    <option value="{{ $ficha->id }}" {{ isset($filtros['ficha']) && $filtros['ficha'] == $ficha->id ? 'selected' : '' }}>{{ $ficha->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary mr-2" style="background: #4b5e82; border: none;"><i class="fas fa-filter mr-1"></i> Filtrar</button>
                                    <a href="{{ route('entrada_salida.index') }}" class="btn btn-outline-secondary"><i class="fas fa-eraser mr-1"></i> Limpiar</a>
                                </div>
                            </div>
                        </form>

                        <!-- Formulario para asignar fecha de salida -->
                        <form action="{{ route('entrada_salida.actualizar_fecha_salida') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group d-flex align-items-end">
                                        <div class="mr-2" style="flex: 0 0 250px;">
                                            <label for="fecha_salida" class="d-block text-dark">Asignar Fecha y Hora de Salida</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light"><i class="fas fa-calendar-check"></i></span>
                                                </div>
                                                <input type="datetime-local" name="fecha_salida" id="fecha_salida" class="form-control @error('fecha_salida') is-invalid @enderror" required>
                                                @error('fecha_salida')
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-custom mr-2" style="height: calc(2.25rem + 2px);"><i class="fas fa-check mr-1"></i> Asignar Fecha</button>
                                        <button type="button" id="seleccionarTodos" class="btn btn-info btn-custom" style="height: calc(2.25rem + 2px);"><i class="fas fa-check-square mr-1"></i> Seleccionar Todos</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabla de resultados -->
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered mt-3">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">Sel.</th>
                                            <th>Nombre</th>
                                            <th>Fecha Ingreso</th>
                                            <th>Fecha Salida</th>
                                            <th>Visitó Granja</th>
                                            <th>Grupo</th>
                                            <th>Ficha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($registros as $registro)
                                            <tr class="checkbox-row">
                                                <td class="text-center">
                                                    @if (is_null($registro->fecha_hora_salida))
                                                        <input type="checkbox" name="ids[]" value="{{ $registro->id }}">
                                                    @endif
                                                </td>
                                                <td>{{ $registro->nombreRelacion->nombre ?? 'N/A' }}</td>
                                                <td>{{ $registro->fecha_hora_ingreso->format('d/m/Y H:i:s') }}</td>
                                                <td>{{ $registro->fecha_hora_salida ? $registro->fecha_hora_salida->format('d/m/Y H:i:s') : 'Pendiente' }}</td>
                                                <td>{{ $registro->visito_ultimas_48h_texto }}</td>
                                                <td>{{ $registro->grupoRelacion->nombre ?? 'N/A' }}</td>
                                                <td>{{ $registro->fichaRelacion->nombre ?? 'N/A' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-muted py-3">No se encontraron registros</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<style>
    /* Estilos para los botones */
    .btn-custom {
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .btn-success.btn-custom {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-success.btn-custom:hover {
        background-color: #218838; /* Verde más oscuro al pasar el mouse */
        border-color: #1e7e34;
    }

    .btn-success.btn-custom:active {
        background-color: #1e7e34; /* Verde aún más oscuro al hacer clic */
        border-color: #1c7430;
    }

    .btn-info.btn-custom {
        background-color: #17a2b8;
        border-color: #17a2b8;
    }

    .btn-info.btn-custom:hover {
        background-color: #138496; /* Cyan más oscuro al pasar el mouse */
        border-color: #117a8b;
    }

    .btn-info.btn-custom:active {
        background-color: #117a8b; /* Cyan aún más oscuro al hacer clic */
        border-color: #10707f;
    }

    /* Hacer la fila clickable */
    .checkbox-row {
        cursor: pointer;
    }
</style>

<script>
    $(document).ready(function () {
        // Resaltar el menú activo
        $('.nav-sidebar a[href="{{ route('entrada_salida.index') }}"]').addClass('active').parents('.nav-item.has-treeview').addClass('menu-open');

        // Seleccionar/Deseleccionar todos
        $('#seleccionarTodos').on('click', function () {
            var checkboxes = $('input[name="ids[]"]');
            var allChecked = checkboxes.length === checkboxes.filter(':checked').length;
            checkboxes.prop('checked', !allChecked);
            $(this).html(allChecked ? '<i class="fas fa-check-square mr-1"></i> Seleccionar Todos' : '<i class="fas fa-square mr-1"></i> Deseleccionar Todos');
        });

        // Hacer clic en la fila para togglear el checkbox
        $('.checkbox-row').on('click', function (e) {
            if (e.target.tagName !== 'INPUT') {
                var checkbox = $(this).find('input[type="checkbox"]');
                if (checkbox.length) {
                    checkbox.prop('checked', !checkbox.prop('checked'));
                }
            }
        });

        // Establecer la fecha y hora local automáticamente
        const fechaSalidaInput = document.getElementById('fecha_salida');
        const now = new Date();
        const localDateTime = now.toISOString().slice(0, 16); // Formato: YYYY-MM-DDTHH:MM
        fechaSalidaInput.value = localDateTime;
    });
</script>
@endsection