@extends('layouts.master')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h1 class="h4 mb-0">Agregar Nueva Especie</h1>
                    </div>
                    
                    <div class="card-body">
                        <form action="{{ route('species.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">Nombre de la especie</label>
                                <input type="text" name="name" id="name" 
                                       class="form-control form-control-lg border-2" 
                                       required
                                       placeholder="Ingrese el nombre científico">
                                <div class="invalid-feedback">
                                    Por favor ingrese un nombre válido.
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i> Registrar Nueva Especie
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Validación del formulario -->
    <script>
        (function () {
            'use strict'
            
            var forms = document.querySelectorAll('.needs-validation')
            
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>

    <!-- SweetAlerts (se mantienen igual) -->
    @if (session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                backdrop: 'rgba(0,0,0,0.4)',
                customClass: {
                    confirmButton: 'btn btn-primary px-4'
                }
            });
        </script>
    @endif

    @if ($errors->any())
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: 'Error',
                text: '{{ $errors->first() }}',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                backdrop: 'rgba(0,0,0,0.4)',
                customClass: {
                    confirmButton: 'btn btn-primary px-4'
                }
            });
        </script>
    @endif
@endsection

@section('styles')
    <style>
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .form-control {
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .btn-primary {
            background-color: #0d6efd;
            border: none;
            padding: 10px 20px;
            transition: background-color 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #0b5ed7;
        }
    </style>
@endsection