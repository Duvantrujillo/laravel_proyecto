@extends('layouts.master')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light px-3 py-5">
    <div class="bg-white shadow-sm rounded-4 p-5 w-100" style="max-width: 700px;">
        <h2 class="text-center mb-4 text-primary">Registrar Usuario</h2>

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
                <label for="name" class="form-label text-secondary">Nombre</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label text-secondary">Apellido</label>
                <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
            </div>

            <div class="mb-3">
                <label for="document" class="form-label text-secondary">Documento</label>
                <input type="text" name="document" class="form-control" value="{{ old('document') }}" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label text-secondary">Teléfono</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label text-secondary">Rol</label>
                <select name="role" class="form-select" required>
                    <option value="pasante" {{ old('role') == 'pasante' ? 'selected' : '' }}>Pasante</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="state" class="form-label text-secondary">Estado</label>
                <select name="state" class="form-select" required>
                    <option value="activo" {{ old('state') == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="bloqueado" {{ old('state') == 'bloqueado' ? 'selected' : '' }}>Bloqueado</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label text-secondary">Contraseña</label>
                <input type="password" name="password" class="form-control" required minlength="6">
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label text-secondary">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" class="form-control" required minlength="6">
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">
                <i class="fas fa-user-plus me-2"></i> Registrar
            </button>
        </form>
    </div>
</div>
@endsection
