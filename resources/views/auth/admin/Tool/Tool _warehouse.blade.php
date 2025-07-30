@extends('layouts.master')

@section('content')
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<div class="container mt-4">
    <h2 class="text-center text-primary mb-4">🛠️ Bodega de Herramientas</h2>

    <div class="table-responsive mx-auto" style="max-width: 95%;">
        <table class="table table-striped table-hover table-sm shadow-sm">
            <thead class="thead-dark small">
                <tr class="text-center">
                    <th>Cantidad</th>
                    <th>Disponibilidad</th>
                    <th>Herramienta</th>
                    <th>Observación</th>
                    @if (auth()->user()->role === 'admin')
                        <th>Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody class="small">
                @foreach ($tools as $obs)
                    <tr>
                        <td class="align-middle text-center">{{ $obs->total_quantity }}</td>
                        <td class="align-middle text-center">{{ $obs->amount }}</td>
                        <td class="align-middle">
                            {{ $obs->product }}
                            <button class="btn btn-link p-0 text-info" data-toggle="modal" data-target="#modalDetalle{{ $obs->id }}">
                                <i class="bi bi-info-circle-fill"></i>
                            </button>
                        </td>
                        <td class="align-middle text-center">
                            <button class="btn btn-link p-0 text-secondary" data-toggle="modal" data-target="#exampleModal{{ $obs->id }}">
                                <i class="bi bi-journal-bookmark-fill"></i>
                            </button>
                        </td>

                        @if (auth()->user()->role === 'admin')
                            <td class="align-middle text-center">
                                <form method="POST" class="d-inline form-eliminar" action="{{ route('Tool.destroy', $obs->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-outline-danger btn-sm btn-delete-swal" title="Eliminar">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-outline-warning btn-sm" data-toggle="modal" data-target="#modalEditar{{ $obs->id }}" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </td>
                        @endif
                    </tr>

                    <!-- Modal Observación -->
                    <div class="modal fade" id="exampleModal{{ $obs->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-sm">
                                <div class="modal-header bg-dark text-white py-2">
                                    <h6 class="modal-title">Observación</h6>
                                    <button type="button" class="close text-white" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body small">
                                    <p>{{ $obs->observation }}</p>
                                </div>
                                <div class="modal-footer py-2">
                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Detalle -->
                    <div class="modal fade" id="modalDetalle{{ $obs->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-sm">
                                <div class="modal-header bg-info text-white py-2">
                                    <h6 class="modal-title">Detalles de {{ $obs->product }}</h6>
                                    <button type="button" class="close text-white" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body small text-center">
                                    @if ($obs->image_path)
                                        <img src="{{ asset('storage/' . $obs->image_path) }}" class="img-fluid rounded mb-2 d-block mx-auto" width="200" >

                                    @endif
                                    @if ($obs->extra_info)
                                        <p class="text-justify"><strong>Información extra:</strong> {{ $obs->extra_info }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modales de Edición -->
@if (auth()->user()->role === 'admin')
    @foreach ($tools as $obs)
        <div class="modal fade" id="modalEditar{{ $obs->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-sm">
                    <div class="modal-header bg-primary text-white py-2">
                        <h6 class="modal-title">Editar Herramienta</h6>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('Tool.update', $obs->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body small">
                            <div class="form-group">
                                <label>Cantidad</label>
                                <input type="number" name="amount" class="form-control form-control-sm" value="{{ old('amount', $obs->amount) }}" required>
                            </div>
                            <div class="form-group">
                                <label>Producto</label>
                                <input type="text" name="product" class="form-control form-control-sm" value="{{ old('product', $obs->product) }}" required>
                            </div>
                            <div class="form-group">
                                <label>Observación</label>
                                <textarea name="observation" class="form-control form-control-sm" rows="2" required>{{ old('observation', $obs->observation) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Información Extra</label>
                                <textarea name="extra_info" class="form-control form-control-sm" rows="2">{{ old('extra_info', $obs->extra_info) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Estado</label>
                                <select name="status" class="form-control form-control-sm" required>
                                    <option value="enabled" {{ old('status', $obs->status) == 'enabled' ? 'selected' : '' }}>Habilitado</option>
                                    <option value="disabled" {{ old('status', $obs->status) == 'disabled' ? 'selected' : '' }}>Deshabilitado</option>
                                </select>
                            </div>
                          
  <div class="form-group">
    <label class="form-label fw-semibold mb-1">Seleccionar imagen</label>
    <div class="input-group align-items-center">
        <input type="file" name="image" id="image-{{ $obs->id }}" class="form-control d-none" accept="image/*">
        <label for="image-{{ $obs->id }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem; width: auto;">
            <i class="bi bi-image me-1"></i>Seleccionar imagen
        </label>
        <div id="image-preview-{{ $obs->id }}" class="ms-3" style="max-width: 100px; max-height: 100px;"></div>
    </div>
    @if ($obs->image_path)
        <div class="mt-2">
            <small>Imagen actual:</small><br>
            <img src="{{ asset('storage/' . $obs->image_path) }}" class="img-thumbnail" style="max-width: 100px;">
        </div>
    @endif
</div>
                        </div>
                        <div class="modal-footer py-2">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endif

<script>
    document.getElementById('image-{{ $obs->id }}').addEventListener('change', function(event) {
        const preview = document.getElementById('image-preview-{{ $obs->id }}');
        preview.innerHTML = ''; // Limpiar vista previa anterior

        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100px';
                img.style.maxHeight = '100px';
                img.className = 'rounded';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });
</script>
<!-- Alertas y Validaciones -->
@if (session('error'))
    <script>
        Swal.fire({ icon: "error", title: "Error", text: '{{ session('error') }}' });
    </script>
@endif

@if (session('correcto'))
    <script>
        Swal.fire({ icon: "success", title: "¡Actualización exitosa!", text: '{{ session('correcto') }}' });
    </script>
@endif

@if (session('update'))
    <script>
        Swal.fire({ icon: "error", title: "Error", text: '{{ session('update') }}' });
    </script>
@endif

@if (session('pendigLoans'))
    <script>
        Swal.fire({ icon: 'error', title: 'Error', text: '{{ session('pendigLoans') }}' });
    </script>
@endif

@if ($errors->any())
    <script>
        Swal.fire({ icon: "error", title: "Error", text: '{{ $errors->first() }}' });
    </script>
@endif

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.querySelectorAll('.btn-delete-swal').forEach(btn => {
        btn.addEventListener('click', function () {
            const form = this.closest('.form-eliminar');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará la herramienta.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C0392B',
                cancelButtonColor: '#7F8C8D',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

<!-- Estilos -->
<style>
    .table th,
    .table td {
        vertical-align: middle;
    }

    .modal-content {
        border-radius: 12px;
    }

    .btn-outline-warning,
    .btn-outline-danger {
        transition: 0.2s;
    }

    textarea {
        resize: none;
    }

    h2 {
        font-weight: 600;
    }

    p {
        text-align: justify;
        margin: 0;
    }

    .form-control-sm,
    .form-control-file {
        font-size: 0.875rem;
    }
</style>
@endsection
