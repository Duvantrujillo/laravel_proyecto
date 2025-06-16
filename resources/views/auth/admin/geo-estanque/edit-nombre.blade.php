@extends('layouts.master')

@section('title', 'Editar Nombre del Estanque')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Tarjeta para editar nombre -->
                <div class="card card-outline card-primary elevation-2" style="border-top: 3px solid #007bff;">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-edit mr-2"></i> Editar Nombre del Estanque</h3>
                    </div>
                    <form action="{{ route('geo.update-name', $pond->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <!-- Mensajes de alerta -->
                            

                            <!-- Campo de nombre -->
                            <div class="form-group">
                                <label for="name">Nuevo nombre del estanque:</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $pond->name) }}" required>
                               
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('geo.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    @if ($errors->any())
        let errorMessages = '';
        @foreach ($errors->all() as $error)
            errorMessages += '{{ $error }}\n';
        @endforeach

        Swal.fire({
            icon: 'error',
            title: 'Error de validación',
            text: errorMessages,
            confirmButtonColor: '#d33',
        });
    @endif

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6',
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33',
        });
    @endif
</script>
@endsection
