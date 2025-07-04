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

    {{-- Aquí agrego tu alert de error igual, sin modificar nada --}}
    @if (session('error'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let error = @json(session('error'));
                let htmlContent = '';

                if (typeof error === 'object') {
                    htmlContent += error.message ? error.message + '<br>' : '';
                } else {
                    htmlContent = error;
                }

                Swal.fire({
                    title: '¡Error!',
                    html: htmlContent,
                    icon: 'error',
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
                    <option value="pasante" {{ request('role') == 'pasante' ? 'selected' : '' }}>Pasante</option>
                </select>
            </div>

            <div class="col-md-3">
                <select name="state" class="form-control">
                    <option value="">-- Filtrar por estado --</option>
                    <option value="activo" {{ request('state') == 'activo' ? 'selected' : '' }}>Activo</option>
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

                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="form-eliminar d-inline">
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

<!-- SweetAlert2 Confirmación doble -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('.form-eliminar');
        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Evita envío inmediato

                // Primera alerta
                Swal.fire({
                    title: '¿Deseas eliminar este usuario?',
                    text: 'Esta acción es importante',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, continuar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d'
                }).then((firstResult) => {
                    if (firstResult.isConfirmed) {
                        // Segunda alerta
                        Swal.fire({
                            title: '¿Estás completamente seguro?',
                            text: 'Si aceptas, no podrás deshacer esta acción',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'No, cancelar',
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#6c757d'
                        }).then((secondResult) => {
                            if (secondResult.isConfirmed) {
                                form.submit(); // Ahora sí se elimina
                            }
                        });
                    }
                });
            });
        });
    });
</script>
@endsection
