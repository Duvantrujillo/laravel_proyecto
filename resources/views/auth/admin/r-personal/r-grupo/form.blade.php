@extends('layouts.master')

@section('title', 'Registrar Grupo y Ficha')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-outline card-primary elevation-2" style="border-top: 3px solid #4b5e82;">
                        <div class="card-header bg-transparent border-0">
                            <h3 class="card-title text-dark"><i class="fas fa-layer-group mr-2"></i> Registrar Tecnologo y
                                Ficha</h3>
                        </div>
                        <div class="card-body p-4">
                            <!-- Formulario para registrar grupo -->
                            <form action="{{ route('grupo.storeGrupo') }}" method="POST" id="grupoForm">
                                @csrf
                                <div class="form-group">
                                    <label for="nombreGrupo" class="text-dark">Nombre Del Nuevo Tecnologo</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light"><i class="fas fa-users"></i></span>
                                        </div>
                                        <input type="text" name="nombre" id="nombreGrupo"
                                            class="form-control @error('nombre') is-invalid @enderror" required>
                                        @error('nombre')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <div id="nombreGrupoError" class="invalid-feedback d-none"></div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block"
                                    style="background: #4b5e82; border: none;" id="submitGrupoBtn"><i
                                        class="fas fa-save mr-1"></i> Guardar Tecnologo</button>
                            </form>

                            <!-- Formulario para registrar ficha -->
                            <form action="{{ route('grupo.storeFicha') }}" method="POST" id="fichaForm" class="mt-4">
                                @csrf
                                <div class="form-group">
                                    <label for="grupo_id" class="text-dark">Seleccione El Tecnologo</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light"><i class="fas fa-users"></i></span>
                                        </div>
                                        <select name="grupo_id" id="grupo_id"
                                            class="form-control @error('grupo_id') is-invalid @enderror" required>
                                            <option value="">Seleccione un Tecnologo</option>
                                            @foreach ($grupos as $grupo)
                                                <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                                            @endforeach
                                        </select>
                                        @error('grupo_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="nombreFicha" class="text-dark">Número de Ficha</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light"><i class="fas fa-id-badge"></i></span>
                                        </div>
                                        <input type="number" name="nombre" id="nombreFicha"
                                            class="form-control @error('nombre') is-invalid @enderror" required
                                            min="0" step="1">
                                        @error('nombre')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <div id="nombreFichaError" class="invalid-feedback d-none"></div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block"
                                    style="background: #4b5e82; border: none;" id="submitFichaBtn"><i
                                        class="fas fa-save mr-1"></i> Guardar Ficha</button>
                            </form>

                            <!-- Mensajes de éxito -->
                            @if (session('success'))
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                <script>
                                    Swal.fire({
                                        title: "{{ session('success') }}",
                                        icon: "success"
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
    <script>
        $(document).ready(function() {
            const nombreGrupoInput = $('#nombreGrupo');
            const nombreFichaInput = $('#nombreFicha');
            const nombreGrupoError = $('#nombreGrupoError');
            const nombreFichaError = $('#nombreFichaError');
            const submitGrupoBtn = $('#submitGrupoBtn');
            const submitFichaBtn = $('#submitFichaBtn');
            let isNombreGrupoValid = false;
            let isNombreFichaValid = false;

            // Validación y conversión a mayúsculas del nombre del grupo
            nombreGrupoInput.on('input', function() {
                let value = $(this).val();
                // Permitir solo letras y espacios
                value = value.replace(/[^a-zA-Z\s]/g, '');
                // Convertir todo a mayúsculas
                value = value.toUpperCase();
                $(this).val(value);

                // Validar duplicados en tiempo real
                if (value.length > 0) {
                    $.ajax({
                        url: '/check-grupo-nombre',
                        method: 'POST',
                        data: {
                            nombre: value,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.exists) {
                                nombreGrupoError.text(
                                    'El nombre que ha ingresado para el Tecnólogo ya existe en el sistema. Por favor, utilice uno diferente.').removeClass(
                                    'd-none');
                                nombreGrupoInput.addClass('is-invalid');
                                isNombreGrupoValid = false;
                            } else {
                                nombreGrupoError.addClass('d-none');
                                nombreGrupoInput.removeClass('is-invalid');
                                isNombreGrupoValid = true;
                            }
                            toggleSubmitGrupoButton();
                        },
                        error: function() {
                            nombreGrupoError.text('Error al verificar el nombre.').removeClass(
                                'd-none');
                            nombreGrupoInput.addClass('is-invalid');
                            isNombreGrupoValid = false;
                            toggleSubmitGrupoButton();
                        }
                    });
                } else {
                    nombreGrupoError.addClass('d-none');
                    nombreGrupoInput.removeClass('is-invalid');
                    isNombreGrupoValid = false;
                    toggleSubmitGrupoButton();
                }
            });

            // Validación del número de ficha
            nombreFichaInput.on('input', function() {
                const value = $(this).val();
                if (value.length > 0) {
                    $.ajax({
                        url: '/check-numero-ficha',
                        method: 'POST',
                        data: {
                            nombre: value,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.exists) {
                                nombreFichaError.text(
                                    'Este número de ficha ya está registrado.').removeClass(
                                    'd-none');
                                nombreFichaInput.addClass('is-invalid');
                                isNombreFichaValid = false;
                            } else {
                                nombreFichaError.addClass('d-none');
                                nombreFichaInput.removeClass('is-invalid');
                                isNombreFichaValid = true;
                            }
                            toggleSubmitFichaButton();
                        },
                        error: function() {
                            nombreFichaError.text('Error al verificar el número de ficha.')
                                .removeClass('d-none');
                            nombreFichaInput.addClass('is-invalid');
                            isNombreFichaValid = false;
                            toggleSubmitFichaButton();
                        }
                    });
                } else {
                    nombreFichaError.addClass('d-none');
                    nombreFichaInput.removeClass('is-invalid');
                    isNombreFichaValid = false;
                    toggleSubmitFichaButton();
                }
            });

            // Habilitar/deshabilitar botones de envío
            function toggleSubmitGrupoButton() {
                submitGrupoBtn.prop('disabled', !isNombreGrupoValid);
            }

            function toggleSubmitFichaButton() {
                submitFichaBtn.prop('disabled', !isNombreFichaValid || !$('#grupo_id').val());
            }

            // Actualizar estado del botón de ficha al cambiar el grupo
            $('#grupo_id').on('change', toggleSubmitFichaButton);

            // Resaltar el menú activo
            $('.nav-sidebar a[href="{{ route('grupo.create') }}"]').addClass('active').parents(
                '.nav-item.has-treeview').addClass('menu-open');
        });
    </script>
@endsection
