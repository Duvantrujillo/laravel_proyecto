@extends('layouts.master')

@section('title', 'Editar Identificador')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-outline card-primary elevation-2">
                    <div class="card-header">
                        <h3 class="card-title">Editar Identificador</h3>
                    </div>
                    <form method="POST" action="{{ route('geo.update', $identificador->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group">
                                <label for="idficador">Identificador</label>
                                <input type="number" class="form-control" id="idficador" name="idficador" value="{{ old('idficador', $identificador->identificador) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="pond_id">Estanque</label>
                                <select class="form-control" name="pond_id" id="pond_id" required>
                                    @foreach($estanques as $pond)
                                        <option value="{{ $pond->id }}" {{ $identificador->pond_id == $pond->id ? 'selected' : '' }}>{{ $pond->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('geo.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6'
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33'
        });
    @endif
</script>

@endsection
