@extends('layouts.master')

@section('content')
<div class="container">
    <h1>Usuarios Registrados</h1>

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
                title: '¡Éxito!',
                html: htmlContent,
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        });
    </script>
@endif


    <!-- Filtro de búsqueda -->
    <form method="GET" action="{{ route('users.index') }}" class="mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-3">
                <input 
                    type="text" 
                    name="document" 
                    class="form-control" 
                    placeholder="Buscar por cédula" 
                    value="{{ request('document') }}">
            </div>

            <div class="col-md-3">
                <select name="role" class="form-control">
                    <option value="">-- Filtrar por rol --</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="usuario" {{ request('role') == 'usuario' ? 'selected' : '' }}>Usuario</option>
                </select>
            </div>

            <div class="col-md-3">
                <select name="state" class="form-control">
                    <option value="">-- Filtrar por estado --</option>
                    <option value="activo" {{ request('state') == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ request('state') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    <option value="bloqueado" {{ request('state') == 'bloqueado' ? 'selected' : '' }}>Bloqueado</option>
                </select>
            </div>

            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Documento</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th>Rol</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->last_name }}</td>
                <td>{{ $user->document }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ ucfirst($user->state) }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Editar</a>

                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Seguro que desea eliminar este usuario?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
