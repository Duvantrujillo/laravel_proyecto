@extends('layouts.master')

@section('title', 'Filtrar Personal')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <!-- Card para el filtro y la tabla -->
                <div class="card card-outline card-primary elevation-2" style="border-top: 3px solid #4b5e82;">
                    <div class="card-header bg-transparent border-0">
                        <h3 class="card-title text-dark"><i class="fas fa-filter mr-2"></i> Filtrar Personal Registrado</h3>
                    </div>
                    <div class="card-body p-4">
                        <!-- Formulario de filtro -->
                        <form id="filtroForm" method="GET" action="{{ route('personal.filtrado') }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="grupo_id" class="text-dark">Tecnologos</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light"><i class="fas fa-users"></i></span>
                                            </div>
                                            <select name="grupo_id" id="grupo_id" class="form-control">
                                                <option value="">Seleccione un Tecnologo</option>
                                                @foreach($grupos as $grupo)
                                                    <option value="{{ $grupo->id }}" {{ request('grupo_id') == $grupo->id ? 'selected' : '' }}>{{ $grupo->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ficha_id" class="text-dark">Ficha</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light"><i class="fas fa-id-badge"></i></span>
                                            </div>
                                            <select name="ficha_id" id="ficha_id" class="form-control">
                                                <option value="">Seleccione una ficha</option>
                                                @if(request('grupo_id') && $fichas)
                                                    @foreach($fichas as $ficha)
                                                        <option value="{{ $ficha->id }}" {{ request('ficha_id') == $ficha->id ? 'selected' : '' }}>{{ $ficha->nombre }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block mt-3" style="background: #4b5e82; border: none;"><i class="fas fa-search mr-1"></i> Filtrar</button>
                        </form>

                        <!-- Tabla de resultados -->
                        <div class="mt-4">
                            @if($personal->isEmpty())
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i> No hay personal registrado con los filtros seleccionados.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Número de Documento</th>
                                                <th>Teléfono</th>
                                                <th>Correo</th>
                                                <th>Tecnologo</th>
                                                <th>Ficha</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($personal as $persona)
                                                <tr>
                                                    <td>{{ $persona->nombre }}</td>
                                                    <td>{{ $persona->numero_documento }}</td>
                                                    <td>{{ $persona->numero_telefono }}</td>
                                                    <td>{{ $persona->correo }}</td>
                                                    <td>{{ $persona->grupo()->first()->nombre ?? 'Sin tecnologo' }}</td>
                                                    <td>{{ $persona->ficha->nombre ?? 'Sin ficha' }}</td>
                                                    <td>
                                                        <a href="{{ route('register.edit', $persona->id) }}" class="btn btn-sm btn-warning">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-danger btn-eliminar" data-id="{{ $persona->id }}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    {{ $personal->appends(request()->query())->links() }}
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

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $('.nav-sidebar a[href="{{ route('personal.filtrado') }}"]').addClass('active').parents('.nav-item.has-treeview').addClass('menu-open');

        $('#grupo_id').change(function () {
            var grupoId = $(this).val();
            if (grupoId) {
                $.ajax({
                    url: "{{ route('getFichas') }}",
                    type: "GET",
                    data: { grupo_id: grupoId },
                    success: function (data) {
                        $('#ficha_id').empty();
                        $('#ficha_id').append('<option value="">Seleccione una ficha</option>');
                        $.each(data, function (key, value) {
                            $('#ficha_id').append('<option value="' + value.id + '">' + value.nombre + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error("Error al obtener las fichas:", error);
                    }
                });
            } else {
                $('#ficha_id').empty();
                $('#ficha_id').append('<option value="">Seleccione una ficha</option>');
            }
        });

        // Confirmación para eliminar
        $('.btn-eliminar').click(function () {
            let id = $(this).data('id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/register/" + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Eliminado', 'El registro fue eliminado correctamente.', 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Error', response.message || 'No se pudo eliminar.', 'error');
                            }
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
