@extends('layouts.master')

@section('content')

<div class="container mt-5">
    <!-- Título centrado -->
    <h1 class="text-center">Lista de Herramientas</h1>

    <!-- Tabla responsive -->
    <div class="table-responsive mx-auto" style="max-width: 90%;">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Cantidad</th>
                    <th>Producto</th>
                    <th>Observación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($observaciones as $obs)
                    <tr>
                        <td>{{ $obs->amount }}</td>
                        <td>{{ $obs->product }}</td>
                        <td>{{ $obs->observation }}</td>
                        <td>
                            <form action="{{ route('observacion.destroy', $obs->id) }}" method="post" style="display:inline;" onsubmit="return confirm('¿Seguro que quieres eliminar esta herramienta?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete">Eliminar</button>
                            </form>                            
                            <button type="button" class="btn btn-update" data-toggle="modal" data-target="#modalEditar{{ $obs->id }}">
                                Editar
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@foreach ($observaciones as $obs)
<div class="modal fade" id="modalEditar{{ $obs->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel{{ $obs->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel{{ $obs->id }}">Editar Herramienta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('observacion.update', $obs->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="amount">Cantidad</label>
                        <input type="number" name="amount" class="form-control" value="{{ $obs->amount }}" required>
                    </div>

                    <div class="form-group">
                        <label for="product">Producto</label>
                        <input type="text" name="product" class="form-control" value="{{ $obs->product }}" required>
                    </div>

                    <div class="form-group">
                        <label for="observation">Observación</label>
                        <textarea name="observation" class="form-control" required>{{ $obs->observation }}</textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-update">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
    alert ("{{session('success')}}")
</script>

<!-- Dependencias de Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">

<style>
    /* Variables de colores combinados (sin cambios) */
    :root {
        --gris-oscuro: #333333;    /* Para texto y bordes */
        --gris-claro: #EDEDED;     /* Fondo sutil */
        --rojo-eliminar: #A93226;  /* Rojo oscuro para eliminar */
        --rojo-hover: #7B241C;     /* Hover más oscuro */
        --verde-actualizar: #196F3D; /* Verde oscuro para actualizar */
        --verde-hover: #0F4C29;    /* Hover más oscuro */
        --azul-fondo: #F5F7FA;     /* Fondo general suave */
        --sombra: rgba(0, 0, 0, 0.1);
    }

    body {
        background-color: var(--azul-fondo);
        font-family: 'Arial', sans-serif;
    }

    h1 {
        color: var(--gris-oscuro);
        font-weight: 600;
        margin-bottom: 50px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        font-size: 2.5rem; /* Título más grande */
    }

    /* Estilos optimizados para la tabla */
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 12px var(--sombra);
    }

    .table {
        margin-bottom: 0;
        background-color: #fff;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead th {
        background-color: var(--gris-oscuro);
        color: #fff;
        font-size: 1.2rem; /* Aumentado */
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 18px 25px; /* Más espaciado */
        border: none;
        text-align: center;
    }

    .table th, .table td {
        vertical-align: middle;
        padding: 18px 25px; /* Más espaciado */
        border: 1px solid var(--gris-claro);
        font-size: 1.1rem; /* Texto más grande */
        color: var(--gris-oscuro);
    }

    .table tbody tr {
        transition: background-color 0.3s ease;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: var(--gris-claro);
    }

    .table-hover tbody tr:hover {
        background-color: #E8ECEF;
    }

    /* Columna de acciones */
    .table td:last-child {
        text-align: center;
        min-width: 220px; /* Ajustado para más espacio */
    }

    /* Botones */
    .btn {
        border-radius: 4px;
        font-weight: 500;
        padding: 10px 20px; /* Botones más grandes */
        margin: 0 8px; /* Más separación */
        transition: all 0.3s ease;
        font-size: 1rem; /* Texto más grande */
    }

    .btn-delete {
        background-color: var(--rojo-eliminar);
        color: #fff;
        border: none;
    }

    .btn-delete:hover {
        background-color: var(--rojo-hover);
    }

    .btn-update {
        background-color: var(--verde-actualizar);
        color: #fff;
        border: none;
    }

    .btn-update:hover {
        background-color: var(--verde-hover);
    }

    /* Modal */
    .modal-content {
        border: 1px solid var(--gris-claro);
        border-radius: 6px;
    }

    .modal-header {
        background-color: var(--gris-oscuro);
        color: #fff;
        border-bottom: none;
    }

    .modal-title {
        font-weight: 500;
        font-size: 1.5rem; /* Título del modal más grande */
    }

    .close {
        color: #fff;
        opacity: 0.8;
        font-size: 1.5rem;
    }

    .close:hover {
        opacity: 1;
    }

    .form-control {
        border-color: var(--gris-claro);
        box-shadow: none;
        border-radius: 4px;
        font-size: 1.1rem; /* Inputs más grandes */
    }

    .form-control:focus {
        border-color: var(--gris-oscuro);
        box-shadow: 0 0 5px rgba(51, 51, 51, 0.3);
    }

    label {
        color: var(--gris-oscuro);
        font-weight: 500;
        font-size: 1.2rem; /* Etiquetas más grandes */
    }

    .btn-secondary {
        background-color: #6C757D;
        border-color: #6C757D;
        font-size: 1rem;
    }

    .btn-secondary:hover {
        background-color: #5A6268;
        border-color: #5A6268;
    }
</style>

@endsection