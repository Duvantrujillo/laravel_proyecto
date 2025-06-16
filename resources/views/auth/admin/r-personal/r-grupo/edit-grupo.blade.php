@extends('layouts.master')

@section('title', 'Editar Tecnólogo')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Editar Tecnólogo</h3>

    <form action="{{ route('grupo.update', $grupo->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nombre">Nombre del Tecnólogo</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $grupo->nombre) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>
@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error de validación',
            html: '{!! implode("<br>", $errors->all()) !!}',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Revisar'
        });
    </script>
@endif

@endsection
