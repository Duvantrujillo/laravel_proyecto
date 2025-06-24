@extends('layouts.master')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="text-center mb-4">Bodega de Herramientas</h1>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('Tool.store') }}" method="POST">
                            @csrf

                            <!-- Campo Cantidad -->
                            <div class="mb-3">
                                <label for="amount" class="form-label">Cantidad</label>
                                <input type="number" name="amount" id="amount" class="form-control" placeholder="Ej: 5"
                                    required>
                            </div>

                            <!-- Campo Producto -->
                            <div class="mb-3">
                                <label for="product" class="form-label">Producto</label>
                                <input type="text" name="product" id="product" class="form-control"
                                    placeholder="Ej: Martillo" required>
                            </div>

                            <!-- Campo Observación -->
                            <div class="mb-4">
                                <label for="observation" class="form-label">Observación</label>
                                <textarea name="observation" id="observation" class="form-control" rows="3" placeholder="Detalles adicionales..."
                                    required></textarea>
                            </div>

                            <!-- Botón de envío -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    Registrar Herramienta
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmSubmit() {
            return confirm('¿Estás seguro de registrar esta herramienta?');
        }
    </script>

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
    @if (session('error'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
@endsection
