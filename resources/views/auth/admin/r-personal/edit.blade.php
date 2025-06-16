@extends('layouts.master')

@section('title', 'Editar Personal')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card card-outline card-primary elevation-2" style="border-top: 3px solid #4b5e82;">
                    <div class="card-header bg-transparent border-0">
                        <h3 class="card-title text-dark">
                            <i class="fas fa-user-edit mr-2"></i> Editar Información del Personal
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('register.update', $persona->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $persona->nombre) }}" required>
                                @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group">
                                <label for="numero_documento">Número de Documento</label>
                                <input type="text" name="numero_documento" class="form-control" value="{{ old('numero_documento', $persona->numero_documento) }}" required>
                                @error('numero_documento') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group">
                                <label for="numero_telefono">Teléfono</label>
                                <input type="text" name="numero_telefono" class="form-control" value="{{ old('numero_telefono', $persona->numero_telefono) }}" required>
                                @error('numero_telefono') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group">
                                <label for="correo">Correo</label>
                                <input type="email" name="correo" class="form-control" value="{{ old('correo', $persona->correo) }}" required>
                                @error('correo') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group">
                                <label for="grupo">Tecnólogo</label>
                                <select name="grupo" id="grupo_id" class="form-control" required>
                                    <option value="">Seleccione un Tecnólogo</option>
                                    @foreach($grupos as $grupo)
                                        <option value="{{ $grupo->id }}" {{ $persona->grupo == $grupo->id ? 'selected' : '' }}>
                                            {{ $grupo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('grupo') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group">
                                <label for="fichas">Ficha</label>
                                <select name="fichas" id="ficha_id" class="form-control" required>
                                    <option value="">Seleccione una Ficha</option>
                                    @foreach($fichas as $ficha)
                                        <option value="{{ $ficha->id }}" {{ $persona->fichas == $ficha->id ? 'selected' : '' }}>
                                            {{ $ficha->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('fichas') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <button type="submit" class="btn btn-primary" style="background: #4b5e82; border: none;">
                                <i class="fas fa-save mr-1"></i> Guardar Cambios
                            </button>
                            <a href="{{ route('personal.filtrado') }}" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        // Carga dinámica de fichas al cambiar grupo
        $('#grupo_id').change(function () {
            var grupoId = $(this).val();
            if (grupoId) {
                $.ajax({
                    url: "{{ route('getFichas') }}",
                    type: "GET",
                    data: { grupo_id: grupoId },
                    success: function (data) {
                        $('#ficha_id').empty().append('<option value="">Seleccione una ficha</option>');
                        $.each(data, function (key, value) {
                            $('#ficha_id').append('<option value="' + value.id + '">' + value.nombre + '</option>');
                        });
                    },
                    error: function () {
                        Swal.fire('Error', 'No se pudieron cargar las fichas', 'error');
                    }
                });
            } else {
                $('#ficha_id').empty().append('<option value="">Seleccione una ficha</option>');
            }
        });

        // SweetAlert para errores de validación
        @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Errores en el formulario',
            html: `{!! implode('<br>', $errors->all()) !!}`,
        });
        @endif

        // SweetAlert para éxito
        @if(session('success'))
        Swal.fire('Éxito', '{{ session('success') }}', 'success');
        @endif
    });
</script>
@endsection
