@extends('layouts.master')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card card-outline card-primary elevation-2" style="border-top: 3px solid #007bff;">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <h3 class="card-title text-dark">
                            <i class="fas fa-table mr-2"></i> Geomembranas y Identificadores
                        </h3>
                        @if (auth()->user()->role === 'admin')
                        <a href="{{ route('geo.create') }}" class="btn btn-gradient-primary shadow-sm rounded-pill px-4 py-2">
                            <i class="fas fa-plus mr-1"></i> Agregar módulo
                        </a>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Geomembrana</th>
                                        <th>Identificadores</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($filtros2 as $item2)
                                        <tr>
                                            <td class="align-middle d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center flex-wrap">
                                                    {{ $item2['pond_name'] }}
                                                    @if (auth()->user()->role === 'admin')
                                                    <a href="{{ route('geo.edit-name', $item2['pond_id']) }}"
                                                        class="btn btn-sm btn-outline-primary rounded-circle ml-2"
                                                        title="Editar nombre">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger rounded-circle ml-2 eliminar-estanque"
                                                        data-id="{{ $item2['pond_id'] }}"
                                                        title="Eliminar estanque">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                    <form id="form-eliminar-{{ $item2['pond_id'] }}"
                                                        action="{{ route('geo.deleteEstanque', $item2['pond_id']) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if (!empty($item2['identificadores']) && count($item2['identificadores']) > 0)
                                                    @foreach ($item2['identificadores'] as $identificador)
                                                        <span class="badge badge-light border shadow-sm p-2 mr-1 d-inline-flex align-items-center flex-wrap">
                                                            {{ $identificador['identificador'] }}
                                                            @if (auth()->user()->role === 'admin')
                                                            <a href="{{ route('geo.edit', $identificador['id']) }}"
                                                                class="btn btn-sm btn-outline-warning rounded-circle ml-2"
                                                                title="Editar identificador">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-danger rounded-circle ml-2 eliminar-identificador"
                                                                data-id="{{ $identificador['id'] }}"
                                                                title="Eliminar identificador">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                            <form id="form-identificador-{{ $identificador['id'] }}"
                                                                action="{{ route('geo.destroy', $identificador['id']) }}"
                                                                method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="badge badge-secondary px-3 py-2">Sin identificadores</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">No hay registros disponibles.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (auth()->user()->role === 'admin')
<script>
    // Eliminar estanque con doble alerta
    document.querySelectorAll('.eliminar-estanque').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            Swal.fire({
                title: '¿Eliminar estanque?',
                text: 'Esto eliminará el estanque y todos sus identificadores.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar',
            }).then(result => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: '¿Estás completamente seguro?',
                        text: 'No podrás deshacer esta acción.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Eliminar',
                        cancelButtonText: 'No, cancelar'
                    }).then(second => {
                        if (second.isConfirmed) {
                            document.getElementById(`form-eliminar-${id}`).submit();
                        }
                    });
                }
            });
        });
    });

    // Eliminar identificador con doble alerta
    document.querySelectorAll('.eliminar-identificador').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            Swal.fire({
                title: '¿Eliminar identificador?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar',
            }).then(result => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: '¿Estás completamente seguro?',
                        text: 'Confirma para eliminar este identificador.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Eliminar',
                        cancelButtonText: 'No, cancelar'
                    }).then(second => {
                        if (second.isConfirmed) {
                            document.getElementById(`form-identificador-${id}`).submit();
                        }
                    });
                }
            });
        });
    });
</script>
@endif

@if (session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            title: '¡Error!',
            html: {!! json_encode(session('error')) !!},
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    });
</script>
@endif

@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            title: '¡Éxito!',
            html: {!! json_encode(session('success')) !!},
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });
    });
</script>
@endif
@endsection
