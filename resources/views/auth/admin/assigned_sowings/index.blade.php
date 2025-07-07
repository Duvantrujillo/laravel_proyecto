@extends('layouts.master')

@section('content')
    <div class="container-fluid mt-3">
        <h4 class="mb-4 text-center text-uppercase">Cosechas Asignadas a Usuarios</h4>

        <div class="text-end mb-3">
            <a href="{{ route('assigned_sowings.create') }}" class="btn btn-success btn-sm">➕ Asignar Nueva Siembra</a>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover align-middle text-sm">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Especie</th>
                        <th>Estanque</th>
                        <th>Identificador</th>
                        <th>Origen</th>
                        <th>Fecha Siembra</th>
                        <th>Asignado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($assignments as $index => $assignment)
                        @if ($assignment->sowing)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $assignment->user->name }}</td>
                                <td>{{ $assignment->user->email }}</td>
                                <td>{{ $assignment->sowing->species->name ?? 'Sin especie' }}</td>
                                <td>{{ $assignment->sowing->identifier->pond->name ?? 'Sin estanque' }}</td>
                                <td>{{ $assignment->sowing->identifier->identificador ?? 'N/A' }}</td>
                                <td>{{ $assignment->sowing->origin ?? 'No disponible' }}</td>
                                <td>{{ \Carbon\Carbon::parse($assignment->sowing->sowing_date)->format('d/m/Y') }}</td>
                                <td>{{ $assignment->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <form class="desasignar-form" data-user="{{ $assignment->user->name }}"
                                        action="{{ route('assigned_sowings.destroy', $assignment->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger px-2 py-0 desasignar-btn">✖</button>
                                    </form>
                                </td>
                            </tr>
                        @else
                            {{-- Opcional: mostrar algo si la siembra no existe --}}
                            <tr>
                                <td colspan="10" class="text-center text-danger">Asignación inválida: la siembra ya no
                                    existe</td>
                            </tr>
                        @endif
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Mostrar alertas de éxito/error tipo toast
        @if (session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if (session('error'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        // Confirmación doble antes de desasignar
        document.querySelectorAll('.desasignar-btn').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.desasignar-form');
                const user = form.dataset.user;

                Swal.fire({
                    title: `¿Desasignar a ${user}?`,
                    text: "Esta acción eliminará la asignación.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, continuar',
                    cancelButtonText: 'Cancelar'
                }).then(result => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: '¿Estás completamente seguro?',
                            text: "Esto no se puede deshacer.",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Sí, desasignar',
                            cancelButtonText: 'Cancelar'
                        }).then(result2 => {
                            if (result2.isConfirmed) {
                                form.submit();
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
