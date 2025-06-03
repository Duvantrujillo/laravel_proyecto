@extends('layouts.Master')

@section('content')
    <h1>Lista de Especies existentes</h1>
    <a href="{{ route('species.create') }}" class="btn btn-primary mb-3">Agregar Especie</a>
    <a href="{{ route('types.create') }}" class="btn btn-secondary mb-3">Agregar Tipo</a>

    <form method="GET" action="{{ route('species.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar especies..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-outline-secondary">Buscar</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Especie</th>
                <th>Tipo</th>
                <th>Accion</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($species as $specie)
                <tr>
                    <td>
                        <span class="species-name">{{ $specie->name }}</span>
                        <form action="{{ route('species.update', $specie) }}" method="POST" class="species-edit-form" style="display:none;">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $specie->name }}" class="form-control" required>
                            <button type="submit" class="btn btn-sm btn-primary mt-1">Guardar</button>
                            <button type="button" class="btn btn-sm btn-secondary mt-1 cancel-edit">Cancelar</button>
                        </form>
                    </td>
                    <td>
                        @foreach ($specie->types as $type)
                            <span class="type-name">{{ $type->name }}</span>
                            <form action="{{ route('types.update', $type) }}" method="POST" class="type-edit-form" style="display:none;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="species_id" value="{{ $specie->id }}">
                                <input type="text" name="name" value="{{ $type->name }}" class="form-control d-inline-block" style="width:auto;" required>
                                <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                                <button type="button" class="btn btn-sm btn-secondary cancel-edit">Cancelar</button>
                            </form>
                            <button class="btn btn-sm btn-warning edit-type">Editar</button>
                            <form action="{{ route('types.destroy', $type) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this type?')">Eliminar</button>
                            </form>
                        @endforeach
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-species">Editar</button>
                        <form action="{{ route('species.destroy', $specie) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta especie? Todos los tipos asociados se eliminarán permanentemente y no se podrán recuperar.')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $species->links() }}

    <script>
        // Toggle species edit form
        document.querySelectorAll('.edit-species').forEach(button => {
            button.addEventListener('click', () => {
                const row = button.closest('tr');
                row.querySelector('.species-name').style.display = 'none';
                row.querySelector('.species-edit-form').style.display = 'block';
            });
        });

        // Toggle type edit form
        document.querySelectorAll('.edit-type').forEach(button => {
            button.addEventListener('click', () => {
                const typeContainer = button.parentElement;
                typeContainer.querySelector('.type-name').style.display = 'none';
                typeContainer.querySelector('.type-edit-form').style.display = 'inline';
                button.style.display = 'none';
            });
        });

        // Cancel edit
        document.querySelectorAll('.cancel-edit').forEach(button => {
            button.addEventListener('click', () => {
                const form = button.closest('form');
                const container = form.parentElement;
                container.querySelector('.species-name, .type-name').style.display = 'inline';
                form.style.display = 'none';
                const editButton = container.querySelector('.edit-type');
                if (editButton) editButton.style.display = 'inline';
            });
        });
    </script>

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
            title: 'Error',
            text: '{{ $errors->first() }}',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

@endsection