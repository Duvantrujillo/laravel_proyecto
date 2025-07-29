@extends('layouts.master')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="text-center mb-4">Tool Warehouse</h1>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <form id="miFormulario" action="{{ route('Tool.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Amount Field -->
                            <div class="mb-3">
                                <label for="amount" class="form-label">Cantidad</label>
                                <input type="number" name="amount" id="amount" class="form-control" placeholder="Ex: 5"
                                    required>
                            </div>

                            <!-- Product Field -->
                            <div class="mb-3">
                                <label for="product" class="form-label">Herramienta</label>
                                <input type="text" name="product" id="product" class="form-control"
                                    placeholder="Ex: Hammer" required>
                            </div>

                            <!-- Observation Field -->
                            <div class="mb-4">
                                <label for="observation" class="form-label">Observation</label>
                                <textarea name="observation" id="observation" class="form-control" rows="3" placeholder="Additional details..."
                                    required></textarea>
                            </div>

                            <!-- Image Upload Field -->
                            <div class="mb-4">
                                <label for="image" class="form-label">Tool Image (optional)</label>
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            </div>

                            <!-- Extra Info Field -->
                            <div class="mb-4">
                                <label for="extra_info" class="form-label">Extra Information (optional)</label>
                                <textarea name="extra_info" id="extra_info" class="form-control" rows="3" placeholder="Manual or automatic info"></textarea>
                            </div>
                            <input type="hidden" name="status" value="enabled">
                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" onclick="return confirmSubmit(event)">
                                    Register Herramienta
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function confirmSubmit(event) {
            event.preventDefault(); // Detiene el envío automático del formulario

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Estás a punto de registrar esta herramienta.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar'
            }).then((firstResult) => {
                if (firstResult.isConfirmed) {
                    // Segunda confirmación
                    Swal.fire({
                        title: '¿Estás completamente seguro?',
                        text: "Esta acción no se puede deshacer.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, registrar',
                        cancelButtonText: 'Cancelar'
                    }).then((secondResult) => {
                        if (secondResult.isConfirmed) {
                            // Enviar el formulario manualmente
                            document.getElementById("miFormulario").submit();
                        }
                    });
                }
            });

            return false; // Previene el envío automático del botón
        }
    </script>



    @if (session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: 'Registro Exitoso',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if (session('error'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: 'error',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    <h1>Texto demasiado largo</h1>

    @if ($errors->any())
        <script>
            swal.fire({
                    title: 'Error',
                    text: '{{$errors->first()}}',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }

            )
        </script>
    @endif
@endsection
