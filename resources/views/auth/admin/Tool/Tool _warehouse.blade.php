@extends('layouts.master')

@section('content')

    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Bodega De Herramientas</h1>

        <div class="table-responsive mx-auto" style="max-width: 90%;">
            <table class="table table-bordered table-hover shadow-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>Cantidad</th>
                        <th>Disponiblidad</th>
                        <th>Herramienta</th>
                        <th>Observación</th>
                        @if (auth()->user()->role === 'admin')
                            <th class="text-center">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tools as $obs)
                        <tr>
                            <td>{{ $obs->total_quantity }}</td>
                            <td>{{ $obs->amount }}</td>
                            <td>{{ $obs->product }}
                                <button class="fa-solid fa-circle-info" data-toggle="modal"
                                    data-target="#modalDetalle{{ $obs->id }}">
                                </button>
                            </td>
                            <td>
                                <button class="fa-solid fa-book-open-reader" data-toggle="modal"
                                    data-target="#exampleModal{{ $obs->id }}">
                                </button>
                            </td>


                            @if (auth()->user()->role === 'admin')
                                <td class="text-center">
                                    <form method="POST" class="d-inline form-eliminar"
                                        action="{{ route('Tool.destroy', $obs->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm btn-delete-swal">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                        data-target="#modalEditar{{ $obs->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            @endif
                        </tr>




                        <!-- Modal observacion -->
                        <div class="modal fade" id="exampleModal{{ $obs->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Observacion</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        {{ $obs->observation }}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- MODAL DETALLE -->
                        <div class="modal fade" id="modalDetalle{{ $obs->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content shadow">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title">Detalles de {{ $obs->product }}</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        @if ($obs->image_path)
                                            <img src="{{ asset('storage/' . $obs->image_path) }}" alt="Imagen herramienta"
                                                class="rounded mx-auto d-block"  width="230">
                                        @endif
                                        @if ($obs->extra_info)
                                            <p><strong>Información Extra:</strong> {{ $obs->extra_info }}</p>
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

    <!-- MODALES DE EDICIÓN -->
    @if (auth()->user()->role === 'admin')
        @foreach ($tools as $obs)
            <div class="modal fade" id="modalEditar{{ $obs->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content shadow">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Editar Herramienta</h5>
                            <button type="button" class="close text-white" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('Tool.update', $obs->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label>Cantidad</label>
                                    <input type="number" name="amount" class="form-control"
                                        value="{{ old('amount', $obs->amount) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Producto</label>
                                    <input type="text" name="product" class="form-control"
                                        value="{{ old('product', $obs->product) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Observación</label>
                                    <textarea name="observation" class="form-control" rows="3" required>{{ old('observation', $obs->observation) }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Información Extra</label>
                                    <textarea name="extra_info" class="form-control" rows="2">{{ old('extra_info', $obs->extra_info) }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Estado</label>
                                    <select name="status" class="form-control" required>
                                        <option value="enabled"
                                            {{ old('status', $obs->status) == 'enabled' ? 'selected' : '' }}>Habilitado
                                        </option>
                                        <option value="disabled"
                                            {{ old('status', $obs->status) == 'disabled' ? 'selected' : '' }}>Deshabilitado
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Imagen</label>
                                    <input type="file" name="image" class="form-control-file">
                                    @if ($obs->image_path)
                                        <div class="mt-2">
                                            <small>Imagen actual:</small><br>
                                            <img src="{{ asset('storage/' . $obs->image_path) }}"
                                                alt="Imagen herramienta" style="width: 100px;">
                                        </div>
                                    @endif
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-success">Guardar cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: "error",
                title: "Error",
                text: '{{ session('error') }}',
            });
        </script>
    @endif



    @if (session('correcto'))
        <script>
            Swal.fire({
                icon: "success",
                text: '{{ session('correcto') }}',
                title: "Actulizacion correctamente!",
            });
        </script>
    @endif

    @if (session('update'))
        <script>
            Swal.fire({
                icon: "error",
                text: '{{ session('update') }}',
                title: "Error",
            });
        </script>
    @endif
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.querySelectorAll('.btn-delete-swal').forEach(btn => {
            btn.addEventListener('click', function() {
                const form = this.closest('.form-eliminar');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción eliminará la herramienta seleccionada.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#A93226',
                    cancelButtonColor: '#6c757d',
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


    @if (session('pendigLoans'))
        <script>
            Swal.fire({
            icon: 'error',
                title: 'Error',
                text: '{{ session('pendigLoans') }}',
            });
        </script>
    @endif

    @if ($errors->any())

    <script>
        swal.fire({
            title: 'Error',
            icon: 'error',
            text: '{{$errors->first()}}',
            confirmButtonText:'ok'
        }

        )
    </script>
        
    @endif

    <!-- Estilos -->
    <style>
        .table th,
        .table td {
            border: 5px solid;
        }

        .btn {
            transition: all 0.3s ease-in-out;
        }

        .modal-content {
            border-radius: 20px;
        }

        textarea {
            resize: none;
        }

        .btn-info {
            color: white;
        }

        p {
            text-align: justify;
        }
    </style>

@endsection
