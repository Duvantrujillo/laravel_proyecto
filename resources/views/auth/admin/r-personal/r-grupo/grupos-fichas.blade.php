@extends('layouts.master')

@section('title', 'Grupos y Fichas')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <!-- Card para Grupos y Fichas -->
                <div class="card card-outline card-primary elevation-2" style="border-top: 3px solid #4b5e82;">
                    <div class="card-header bg-transparent border-0">
                        <h3 class="card-title text-dark">
                            <i class="fas fa-users mr-2"></i> Tecnologos y Fichas Existentes
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        @if($grupos->isEmpty())
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i> No hay grupos registrados.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Tecnologos</th>
                                            <th>Fichas Asociadas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($grupos as $grupo)
                                            <tr>
                                                <td>
                                                    {{ $grupo->nombre }}

                                                    @auth
                                                        @if (auth()->user()->role === 'admin')
                                                            <a href="{{ route('grupo.edit', $grupo->id) }}" class="ml-2 text-primary" title="Editar grupo">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('grupo.destroy', $grupo->id) }}" method="POST" class="d-inline-block form-eliminar-grupo">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="btn btn-sm btn-danger ml-1 btn-eliminar-grupo" title="Eliminar grupo">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endauth
                                                </td>
                                                <td>
                                                    @if(isset($fichasPorGrupo[$grupo->id]['numeros']) && !empty($fichasPorGrupo[$grupo->id]['numeros']))
                                                        @foreach($fichasPorGrupo[$grupo->id]['numeros'] as $ficha)
                                                            <span class="badge badge-secondary mr-1" style="background-color: #6c757d;">
                                                                {{ is_object($ficha) ? $ficha->nombre : $ficha }}
                                                                @if(is_object($ficha))
                                                                    @auth
                                                                        @if (auth()->user()->role === 'admin')
                                                                            <a href="{{ route('ficha.edit', $ficha->id) }}" class="btn btn-sm btn-primary ml-1" title="Editar ficha" style="padding: 0 6px;">
                                                                                <i class="fas fa-edit"></i>
                                                                            </a>
                                                                            <form action="{{ route('ficha.destroy', $ficha->id) }}" method="POST" class="d-inline form-eliminar-ficha">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="button" class="btn btn-sm btn-danger ml-1 btn-eliminar-ficha" title="Eliminar ficha" style="padding: 0 5px;">
                                                                                    <i class="fas fa-trash-alt"></i>
                                                                                </button>
                                                                            </form>
                                                                        @endif
                                                                    @endauth
                                                                @endif
                                                            </span>
                                                        @endforeach
                                                    @else
                                                        Ninguna
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<!-- CDN de SweetAlert si no lo tienes ya -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        // Resaltar el menú activo
        $('.nav-sidebar a[href="{{ route('grupos-fichas.index') }}"]').addClass('active').parents('.nav-item.has-treeview').addClass('menu-open');

        // Eliminar grupo con doble confirmación
        $('.btn-eliminar-grupo').click(function () {
            const form = $(this).closest('form');
            Swal.fire({
                title: '¿Eliminar grupo?',
                text: "Esto también eliminará todas las fichas asociadas.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: '¿Estás completamente seguro?',
                        text: "Esta acción no se puede deshacer.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar definitivamente',
                        cancelButtonText: 'Cancelar'
                    }).then((secondResult) => {
                        if (secondResult.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });

        // Eliminar ficha con doble confirmación
        $('.btn-eliminar-ficha').click(function () {
            const form = $(this).closest('form');
            Swal.fire({
                title: '¿Eliminar ficha?',
                text: "Esta acción eliminará solo la ficha seleccionada.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: '¿Confirmas la eliminación?',
                        text: "La ficha será eliminada de forma permanente.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((secondResult) => {
                        if (secondResult.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
