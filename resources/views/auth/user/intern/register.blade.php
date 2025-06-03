@extends('layouts.master')

@section('content')
<div class="container">
    <h1>Registrar Usuario</h1>

    @if (session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const data = @json(session('success'));
            Swal.fire({
                title: '¡Registro exitoso!',
                html: `Correo: <b>${data.email}</b><br>Contraseña: <b>${data.password}</b>`,
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        });
    </script>
    @endif

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

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Apellido</label>
            <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
        </div>

        <div class="mb-3">
            <label for="document" class="form-label">Documento</label>
            <input type="text" name="document" class="form-control" value="{{ old('document') }}" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Teléfono</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Rol</label>
            <select name="role" class="form-select" required>
                <option value="usuario" {{ old('role') == 'usuario' ? 'selected' : '' }}>Usuario</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="state" class="form-label">Estado</label>
            <select name="state" class="form-select" required>
                <option value="activo" {{ old('state') == 'activo' ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ old('state') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                <option value="bloqueado" {{ old('state') == 'bloqueado' ? 'selected' : '' }}>Bloqueado</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required minlength="6">
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" required minlength="6">
        </div>

        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>
@endsection
