@extends('layouts.master')
@section('content')
    <h1>Agregar Nuevo Tipo De Especie</h1>
    <form action="{{ route('types.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="species_id" class="form-label">Especies Existente</label>
            <select name="species_id" id="species_id" class="form-control" required>
                <option value="">Selecciona La Especie</option>
                @foreach ($species as $specie)
                    <option value="{{ $specie->id }}">{{ $specie->name }}</option>
                @endforeach
            </select>
            @error('species_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Nombre Del Tipo De Especie</label>
            <input type="text" name="name" id="name" class="form-control" required>

        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>

    @if ($errors->any())
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: 'Error',
                text: '{{ $errors->first() }}', // O reemplaza por implode para todos los errores
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
@endsection
