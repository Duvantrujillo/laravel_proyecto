@extends('layouts.master')

@section('content')
<div class="container">
    <h1>Editar Usuario</h1>

    @if ($errors->any())
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Errores encontrados',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                icon: 'error',
                confirmButtonText: 'Corregir'
            });
        });
    </script>
    @endif

    @if (session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let success = @json(session('success'));
            let htmlContent = '';

            if (typeof success === 'object') {
                htmlContent += success.message ? success.message + '<br>' : '';
                if (success.password) {
                    htmlContent += '<strong>Nueva Contraseña:</strong> ' + success.password;
                }
            } else {
                htmlContent = success;
            }

            Swal.fire({
                title: 'Éxito',
                html: htmlContent,
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        });
    </script>
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Apellido</label>
            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}" required>
        </div>

        <div class="mb-3">
            <label for="document" class="form-label">Documento</label>
            <input type="text" name="document" class="form-control" value="{{ old('document', $user->document) }}" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Teléfono</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Rol</label>
            <select name="role" class="form-select" required>
                <option value="usuario" {{ old('role', $user->role) == 'usuario' ? 'selected' : '' }}>Usuario</option>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="state" class="form-label">Estado</label>
            <select name="state" class="form-select" required>
                <option value="activo" {{ old('state', $user->state) == 'activo' ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ old('state', $user->state) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                <option value="bloqueado" {{ old('state', $user->state) == 'bloqueado' ? 'selected' : '' }}>Bloqueado</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Nueva Contraseña (opcional)</label>
            <input type="password" name="password" class="form-control" minlength="6">
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" minlength="6">
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
