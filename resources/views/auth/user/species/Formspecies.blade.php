@extends('layouts.master')


@section('content')
    <h1>Agregar Nueva Especie</h1>
    <form action="{{ route('species.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nombre de la especie</label>
            <input type="text" name="name" id="name" class="form-control" required>
            
        </div>
        <button type="submit" class="btn btn-primary">Registrar Nueva Especie</button>
    </form>
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