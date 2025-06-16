@extends('layouts.master')

@section('title', 'Editar Ficha')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Editar Ficha</h3>

    <form action="{{ route('ficha.update', $ficha->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nombre">Número de Ficha</label>
            <input type="number" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $ficha->nombre) }}" required>
            @error('nombre')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="grupo_id">Grupo Asociado</label>
            <select name="grupo_id" id="grupo_id" class="form-control" required>
                @foreach($grupos as $grupo)
                    <option value="{{ $grupo->id }}" {{ $ficha->grupo_id == $grupo->id ? 'selected' : '' }}>{{ $grupo->nombre }}</option>
                @endforeach
            </select>
            @error('grupo_id')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: "{{ session('success') }}",
            confirmButtonText: 'Aceptar'
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Errores encontrados',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonText: 'Revisar'
        });
    @endif
</script>
@endsection

@endsection
