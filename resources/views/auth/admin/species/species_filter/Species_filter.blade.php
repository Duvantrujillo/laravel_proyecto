@extends('layouts.Master')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="card card-outline card-success">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-fish"></i> Lista de Especies</h5>
                @if (auth()->user()->role === 'admin')
                    <div>
                        <a href="{{ route('species.create') }}" class="btn btn-success btn-sm me-2">Agregar Especie</a>
                        <a href="{{ route('types.create') }}" class="btn btn-secondary btn-sm">Agregar Tipo</a>
                    </div>
                @endif
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('species.index') }}" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Buscar especies..." value="{{ $search ?? '' }}">
                        <button type="submit" class="btn btn-outline-secondary">Buscar</button>
                    </div>
                </form>

                <table class="table table-bordered table-hover table-sm text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Especie</th>
                            <th>Tipos Asociados</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($species as $specie)
                            <tr>
                                <td class="align-middle">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold species-name">{{ $specie->name }}</span>
                                        @if (auth()->user()->role === 'admin')
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-warning edit-species" title="Editar especie"><i class="fas fa-edit"></i></button>
                                                <form action="{{ route('species.destroy', $specie) }}" method="POST" class="form-delete-species d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-delete-species" title="Eliminar especie"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>

                                    @if (auth()->user()->role === 'admin')
                                        <form action="{{ route('species.update', $specie) }}" method="POST" class="species-edit-form mt-2" style="display:none;">
                                            @csrf @method('PUT')
                                            <input type="text" name="name" value="{{ $specie->name }}" class="form-control form-control-sm" required>
                                            <div class="mt-1 d-flex gap-1">
                                                <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                                                <button type="button" class="btn btn-secondary btn-sm cancel-edit">Cancelar</button>
                                            </div>
                                        </form>
                                    @endif
                                </td>

                                <td class="align-middle">
                                    @forelse ($specie->types as $type)
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="badge bg-dark type-name">{{ $type->name }}</span>
                                            @if (auth()->user()->role === 'admin')
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-warning edit-type" title="Editar tipo"><i class="fas fa-edit"></i></button>
                                                    <form action="{{ route('types.destroy', $type) }}" method="POST" class="form-delete-type d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-delete-type" title="Eliminar tipo"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>

                                        @if (auth()->user()->role === 'admin')
                                            <form action="{{ route('types.update', $type) }}" method="POST" class="type-edit-form" style="display:none;">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="species_id" value="{{ $specie->id }}">
                                                <div class="d-flex gap-1 mb-1">
                                                    <input type="text" name="name" value="{{ $type->name }}" class="form-control form-control-sm" required>
                                                    <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                                                    <button type="button" class="btn btn-secondary btn-sm cancel-edit">Cancelar</button>
                                                </div>
                                            </form>
                                        @endif
                                    @empty
                                        <span class="text-muted">Ninguno</span>
                                    @endforelse
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $species->links() }}
            </div>
        </div>
    </div>

    {{-- SweetAlert + Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Mostrar formulario editar especie
        document.querySelectorAll('.edit-species').forEach(button => {
            button.addEventListener('click', () => {
                const row = button.closest('td');
                row.querySelector('.species-name').style.display = 'none';
                row.querySelector('.species-edit-form').style.display = 'block';
            });
        });

        // Mostrar formulario editar tipo
        document.querySelectorAll('.edit-type').forEach(button => {
            button.addEventListener('click', () => {
                const container = button.closest('td') || button.parentElement.parentElement;
                container.querySelector('.type-name').style.display = 'none';
                container.querySelector('.type-edit-form').style.display = 'block';
                button.style.display = 'none';
            });
        });

        // Cancelar edición
        document.querySelectorAll('.cancel-edit').forEach(button => {
            button.addEventListener('click', () => {
                const form = button.closest('form');
                const container = form.parentElement;
                const speciesName = container.querySelector('.species-name');
                const typeName = container.querySelector('.type-name');
                const editBtn = container.querySelector('.edit-type');
                if (speciesName) speciesName.style.display = 'inline';
                if (typeName) typeName.style.display = 'inline';
                if (editBtn) editBtn.style.display = 'inline';
                form.style.display = 'none';
            });
        });

        // SweetAlert eliminar especie
        document.querySelectorAll('.btn-delete-species').forEach(button => {
            button.addEventListener('click', () => {
                Swal.fire({
                    title: '¿Eliminar Especie?',
                    text: 'Se eliminará esta especie y sus tipos asociados.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then(result => {
                    if (result.isConfirmed) {
                        button.closest('form').submit();
                    }
                });
            });
        });

        // SweetAlert eliminar tipo
        document.querySelectorAll('.btn-delete-type').forEach(button => {
            button.addEventListener('click', () => {
                Swal.fire({
                    title: '¿Eliminar Tipo?',
                    text: 'Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then(result => {
                    if (result.isConfirmed) {
                        button.closest('form').submit();
                    }
                });
            });
        });
    </script>

    {{-- Alertas --}}

    @if (session('success'))
        <script>
            Swal.fire({
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

    {{-- Alertas para errores --}}
    @if (session('error'))
        <script>
            Swal.fire({
                title: 'Error',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                title: 'Error',
                text: '{{ $errors->first() }}',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

</section>
@endsection
