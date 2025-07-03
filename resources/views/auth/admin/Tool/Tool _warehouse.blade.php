@extends('layouts.master')

@section('content')

<div class="container mt-5">
    <h1 class="text-center text-primary mb-4">🛠️ Lista de Herramientas</h1>

    <div class="table-responsive mx-auto" style="max-width: 90%;">
        <table class="table table-bordered table-hover shadow-sm">
            <thead class="thead-dark">
                <tr>
                    <th>Cantidad</th>
                    <th>Producto</th>
                    <th>Observación</th>
                    @if (auth()->user()->role === 'admin')
                        <th class="text-center">Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($tools as $obs)
                    <tr>
                        <td>{{ $obs->amount }}</td>
                        <td>{{ $obs->product }}</td>
                        <td>{{ $obs->observation }}</td>
                        @if (auth()->user()->role === 'admin')
                            <td class="text-center">
                                <form method="POST" class="d-inline form-eliminar" data-id="{{ $obs->id }}" action="{{ route('Tool.destroy', $obs->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm btn-delete-swal" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEditar{{ $obs->id }}" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modales solo para admin --}}
@if (auth()->user()->role === 'admin')
    @foreach ($tools as $obs)
        <div class="modal fade" id="modalEditar{{ $obs->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel{{ $obs->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content shadow">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalEditarLabel{{ $obs->id }}">Editar Herramienta</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('Tool.update', $obs->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ $obs->id }}">

                            <div class="form-group">
                                <label for="amount">Cantidad</label>
                                <input type="number" name="amount" class="form-control" value="{{ old('amount', $obs->amount) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="product">Producto</label>
                                <input type="text" name="product" class="form-control" value="{{ old('product', $obs->product) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="observation">Observación</label>
                                <textarea name="observation" class="form-control" rows="3" required>{{ old('observation', $obs->observation) }}</textarea>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success">Guardar cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

{{-- ALERTAS --}}
@if (session('correcto'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: '{{ session('correcto') }}',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

@if (session('eliminada'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: '{{ session('eliminada') }}',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

@if (session('update'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: '¡Producto duplicado!',
            text: '{{ session('update') }}',
            confirmButtonText: 'Cambiar nombre',
            confirmButtonColor: '#A93226'
        }).then(() => {
            const idModal = '{{ old('id') }}';
            if (idModal) {
                $('#modalEditar' + idModal).modal('show');
            }
        });
    </script>
@endif

{{-- ✅ NUEVA ALERTA DE ERROR POR RELACIONES --}}
@if (session('error'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: '¡Error!',
                html: @json(session('error')),
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        });
    </script>
@endif

@if (auth()->user()->role === 'admin')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.btn-delete-swal').forEach(btn => {
            btn.addEventListener('click', function () {
                const form = this.closest('.form-eliminar');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción eliminará la herramienta seleccionada.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#A93226',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: '¿Estás completamente seguro?',
                            text: "¡Esta acción no se puede deshacer!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#A93226',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Eliminar definitivamente',
                            cancelButtonText: 'Cancelar'
                        }).then((secondConfirm) => {
                            if (secondConfirm.isConfirmed) {
                                form.submit();
                            }
                        });
                    }
                });
            });
        });
    </script>
@endif

<!-- Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Custom Elegant Styles -->
<style>
    .table th, .table td {
        vertical-align: middle !important;
    }

    .btn {
        transition: all 0.3s ease-in-out;
    }

    .btn i {
        margin-right: 4px;
    }

    .btn-warning:hover {
        background-color: #f0ad4e;
        border-color: #eea236;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c9302c;
        border-color: #ac2925;
        color: white;
    }

    .btn-success:hover {
        background-color: #449d44;
        border-color: #398439;
        color: white;
    }

    .modal-header {
        border-bottom: none;
    }

    .modal-footer {
        border-top: none;
    }

    .modal-content {
        border-radius: 10px;
    }

    textarea {
        resize: none;
    }
</style>

@endsection
