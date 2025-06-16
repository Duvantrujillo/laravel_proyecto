@extends('layouts.master')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <!-- Tarjeta principal -->
                    <div class="card card-outline card-primary elevation-2" style="border-top: 3px solid #007bff;">
                        <div class="card-header bg-transparent border-0">
                            <h3 class="card-title text-dark"><i class="fas fa-water mr-2"></i> Gestión de Estanques y
                                Geomembranas</h3>
                        </div>
                        <div class="card-body p-4">
                            <!-- Formulario para agregar nuevo estanque o geomembrana -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="text-dark mb-3"><i class="fas fa-plus-circle mr-1"></i> Agregar Nuevo</h5>
                                    <form action="{{ route('geo.store') }}" method="post">
                                        @csrf
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light"><i class="fas fa-tag"></i></span>
                                            </div>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="Nombre del estanque o geomembrana" required>
                                            @error('name')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary"
                                                    style="background: #007bff; border: none;">
                                                    <i class="fas fa-save mr-1"></i> Guardar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Separador -->
                            <hr class="my-4" style="border-top: 1px dashed #dcdcdc;">

                            <!-- Formulario para seleccionar estanque -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="text-dark mb-3"><i class="fas fa-list-alt mr-1"></i> Seleccionar Estanque
                                    </h5>
                                    <form action="{{ route('pond.store') }}" method="post">
                                        @csrf
                                        <div class="row">
                                            <!-- Identificador -->
                                            <div class="col-md-6 mb-3">
                                                <label for="idficador" class="d-block text-dark">Identificador</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-light"><i
                                                                class="fas fa-hashtag"></i></span>
                                                    </div>
                                                    <input type="number" name="idficador"
                                                        class="form-control @error('idficador') is-invalid @enderror"
                                                        placeholder="Ingresa un número único" required>
                                                    @error('idficador')
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Selección de estanque -->
                                            <div class="col-md-6 mb-3">
                                                <label for="pond_id" class="d-block text-dark">Estanque</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-light"><i
                                                                class="fas fa-water"></i></span>
                                                    </div>
                                                    <select name="pond_id" id="pond_id"
                                                        class="form-control @error('pond_id') is-invalid @enderror"
                                                        required>
                                                        <option value="">Selecciona un estanque</option>
                                                        @foreach ($filtros as $f)
                                                            <option value="{{ $f->id }}">{{ $f->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('pond_id')
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Botón de envío -->
                                        <button type="submit" class="btn btn-primary btn-block"
                                            style="background: #007bff; border: none;">
                                            <i class="fas fa-check mr-1"></i> Confirmar Selección
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Mensajes de éxito o error -->
                            @if (session('success'))
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                <script>
                                    Swal.fire({
                                        title: '¡Éxito!',
                                        text: '{{ session('success') }}',
                                        icon: 'success',
                                        confirmButtonText: 'Aceptar'
                                    });
                                </script>
                            @endif
                            @if (session('error'))
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                <script>
                                    Swal.fire({
                                        title: 'error!',
                                        text: '{{ session('error') }}',
                                        icon: 'error',
                                        confirmButtonText: 'Aceptar'
                                    });
                                </script>
                            @endif

                            @if ($errors->any() && !session('success'))
                                <div class="alert alert-danger alert-dismissible mt-3 fade show" role="alert">
                                    <i class="fas fa-exclamation-circle mr-2"></i> Por favor, revisa los campos marcados.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            @endif





                            @if (session('error'))
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                <script>
                                    Swal.fire({
                                        title: 'Error',
                                        text: '{{ session('error') }}',
                                        icon: 'error',
                                        confirmButtonText: 'Aceptar'
                                    });
                                </script>
                            @endif
                            @if (session('success'))
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                <script>
                                    Swal.fire({
                                        title: '¡Éxito!',
                                        text: '{{ session('success') }}',
                                        icon: 'success',
                                        confirmButtonText: 'Aceptar'
                                    });
                                </script>
                            @endif
                            @if ($errors->any())
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                <script>
                                    Swal.fire({
                                        title: 'Error de validación',
                                        text: '{{ $errors->first() }}',
                                        icon: 'error',
                                        confirmButtonText: 'Aceptar'
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

@section('styles')
    <style>
        /* Ajustes adicionales para mejorar el diseño */
        .card {
            border-radius: 8px;
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .input-group-text {
            border-radius: 6px 0 0 6px;
        }

        .form-control {
            border-radius: 0 6px 6px 0;
        }

        .btn-primary {
            font-weight: 500;
            padding: 10px;
        }

        .btn-primary:hover {
            background: #0056b3 !important;
        }
    </style>
@endsection
