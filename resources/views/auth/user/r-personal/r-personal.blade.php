@extends('layouts.master')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card card-outline card-primary elevation-2" style="border-top: 3px solid #4b5e82;">
                        <div class="card-header bg-transparent border-0">
                            <h3 class="card-title text-dark"><i class="fas fa-user-plus mr-2"></i> Registrar Personal</h3>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('register.store') }}" method="POST" id="registerForm">
                                @csrf
                                <div class="row">
                                    <!-- Columna izquierda -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nombre" class="text-dark">Nombre Completo</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light"><i
                                                            class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" name="nombre" id="nombre"
                                                    class="form-control @error('nombre') is-invalid @enderror"
                                                    placeholder="Nombre Completo" value="{{ old('nombre') }}" required>
                                                @error('nombre')
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="numero_documento" class="text-dark">Número de Documento</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light"><i
                                                            class="fas fa-id-card"></i></span>
                                                </div>
                                                <input type="text" name="numero_documento" id="numero_documento"
                                                    class="form-control @error('numero_documento') is-invalid @enderror"
                                                    placeholder="Número de Documento" value="{{ old('numero_documento') }}"
                                                    required>
                                                @error('numero_documento')
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                                <div id="numeroDocumentoError" class="invalid-feedback d-none"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="numero_telefono" class="text-dark">Número de Teléfono</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light"><i
                                                            class="fas fa-phone"></i></span>
                                                </div>
                                                <input type="tel" name="numero_telefono" id="numero_telefono"
                                                    class="form-control @error('numero_telefono') is-invalid @enderror"
                                                    placeholder="Número de Teléfono" value="{{ old('numero_telefono') }}"
                                                    required>
                                                @error('numero_telefono')
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Columna derecha -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="correo" class="text-dark">Correo Electrónico</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light"><i
                                                            class="fas fa-envelope"></i></span>
                                                </div>
                                                <input type="email" name="correo" id="correo"
                                                    class="form-control @error('correo') is-invalid @enderror"
                                                    placeholder="Correo Electrónico" value="{{ old('correo') }}" required>
                                                @error('correo')
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="grupo_id" class="text-dark">Tecnologo</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light"><i
                                                            class="fas fa-users"></i></span>
                                                </div>
                                                <select name="grupo" id="grupo_id"
                                                    class="form-control @error('grupo') is-invalid @enderror" required>
                                                    <option value="">Seleccione el Tecnologo</option>
                                                    @if (isset($grupos))
                                                        @foreach ($grupos as $grupo)
                                                            <option value="{{ $grupo->id }}"
                                                                {{ old('grupo') == $grupo->id ? 'selected' : '' }}>
                                                                {{ $grupo->nombre }}</option>
                                                        @endforeach
                                                    @else
                                                        <option value="">No hay grupos disponibles</option>
                                                    @endif
                                                </select>
                                                @error('grupo')
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="ficha_id" class="text-dark">Ficha</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light"><i
                                                            class="fas fa-id-badge"></i></span>
                                                </div>
                                                <select name="fichas" id="ficha_id"
                                                    class="form-control @error('fichas') is-invalid @enderror" required>
                                                    <option value="">Seleccione la ficha</option>
                                                </select>
                                                @error('fichas')
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block mt-3"
                                    style="background: #4b5e82; border: none;" id="submitBtn"><i
                                        class="fas fa-save mr-1"></i> Registrar Aprendiz</button>
                            </form>

                            <!-- Mensajes -->
                            @if (session('success'))
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                <script>
                                    Swal.fire({
                                        title: '{{ session('success') }}',
                                        icon: 'success',
                                        confirmButtonText: 'Aceptar'
                                    });
                                </script>
                            @endif

                            @if ($errors->any() && !session('success'))
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                <script>
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Por favor, corrige los errores en el formulario.',
                                        icon: 'error',
                                        confirmButtonText: 'Entendido'
                                    });
                                </script>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Resaltar el menú activo
            $('.nav-sidebar a[href="{{ route('register.create') }}"]').addClass('active').parents(
                '.nav-item.has-treeview').addClass('menu-open');

            // Capitalizar la primera letra y después de cada espacio en el campo Nombre
            $('#nombre').on('input', function() {
                let value = $(this).val();
                value = value.toLowerCase().replace(/(^|\s)\w/g, char => char.toUpperCase());
                $(this).val(value);
            });

            // Verificar si el número de documento ya existe
            let isNumeroDocumentoValid = false;
            $('#numero_documento').on('input', function() {
                const value = $(this).val();
                const numeroDocumentoError = $('#numeroDocumentoError');
                const submitBtn = $('#submitBtn');

                if (value.length > 0) {
                    $.ajax({
                        url: '{{ route('check.numero.documento') }}',
                        method: 'POST',
                        data: {
                            numero_documento: value,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.exists) {
                                numeroDocumentoError.text(
                                        'Este número de documento ya está registrado.')
                                    .removeClass('d-none');
                                $('#numero_documento').addClass('is-invalid');
                                isNumeroDocumentoValid = false;
                            } else {
                                numeroDocumentoError.addClass('d-none');
                                $('#numero_documento').removeClass('is-invalid');
                                isNumeroDocumentoValid = true;
                            }
                            submitBtn.prop('disabled', !isNumeroDocumentoValid);
                        },
                        error: function() {
                            numeroDocumentoError.text(
                                'Error al verificar el número de documento.').removeClass(
                                'd-none');
                            $('#numero_documento').addClass('is-invalid');
                            isNumeroDocumentoValid = false;
                            submitBtn.prop('disabled', true);
                        }
                    });
                } else {
                    numeroDocumentoError.addClass('d-none');
                    $('#numero_documento').removeClass('is-invalid');
                    isNumeroDocumentoValid = false;
                    submitBtn.prop('disabled', true);
                }
            });

            // Cargar fichas dinámicamente según el grupo seleccionado
            $('#grupo_id').change(function() {
                var grupoId = $(this).val();
                if (grupoId) {
                    $.ajax({
                        url: "{{ route('getFichas') }}",
                        type: "GET",
                        data: {
                            grupo_id: grupoId
                        },
                        success: function(data) {
                            console.log(data);
                            $('#ficha_id').empty();
                            $('#ficha_id').append(
                                '<option value="">Seleccione una ficha</option>');
                            $.each(data, function(key, value) {
                                $('#ficha_id').append('<option value="' + value.id +
                                    '">' + value.nombre + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al obtener las fichas:", error);
                        }
                    });
                } else {
                    $('#ficha_id').empty();
                    $('#ficha_id').append('<option value="">Seleccione una ficha</option>');
                }
            });
        });
    </script>
@endsection
