@extends('layouts.master')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Asignar Siembra a un Pasante</h5>
                </div>

                <div class="card-body">
                    <form id="asignarForm" action="{{ route('assigned_sowings.store') }}" method="POST">
                        @csrf

                        {{-- Campo: Pasante --}}
                        <div class="mb-4">
                            <label for="user_id" class="form-label fw-semibold">Pasante</label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="" selected disabled>Selecciona un pasante</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Campo: Siembra --}}
                        <div class="mb-4">
                            <label for="sowing_id" class="form-label fw-semibold">Siembra</label>
                            <select name="sowing_id" id="sowing_id" class="form-select" required>
                                <option value="" selected disabled>Selecciona una siembra</option>
                                @foreach ($sowings as $sowing)
                                    <option value="{{ $sowing->id }}"
                                        data-especie="{{ $sowing->species->name ?? 'Sin especie' }}"
                                        data-estanque="{{ $sowing->identifier->identificador ?? 'Sin ID' }}"
                                        data-zona="{{ $sowing->identifier->pond->name ?? 'Sin zona' }}"
                                        data-fecha="{{ \Carbon\Carbon::parse($sowing->sowing_date)->format('d/m/Y') }}">
                                        {{ $sowing->species->name ?? 'Sin especie' }}
                                        -
                                        ({{ $sowing->identifier->pond->name ?? 'Sin nombre' }}
                                        {{ $sowing->identifier->identificador ?? 'Sin identificador' }})
                                        -
                                        {{ $sowing->origin }}
                                        -
                                        {{ \Carbon\Carbon::parse($sowing->sowing_date)->format('d/m/Y') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Botón --}}
                        <div class="d-grid">
                            <button type="button" id="confirmAssignBtn" class="btn btn-success">
                                Asignar Siembra
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('confirmAssignBtn').addEventListener('click', function () {
        const userSelect = document.getElementById('user_id');
        const sowingSelect = document.getElementById('sowing_id');
        const userName = userSelect.options[userSelect.selectedIndex]?.text || 'el pasante';
        const selectedSowing = sowingSelect.options[sowingSelect.selectedIndex];

        // ❌ Validación si no hay campos seleccionados
        if (!userSelect.value || !sowingSelect.value) {
            Swal.fire({
                icon: 'error',
                title: 'Faltan campos',
                text: 'Debes seleccionar un pasante y una siembra.',
                confirmButtonText: 'Entendido'
            });
            return;
        }

        // ✅ Compactar la descripción
        const especie = selectedSowing.dataset.especie;
        const estanque = selectedSowing.dataset.estanque;
        const zona = selectedSowing.dataset.zona;
        const fecha = selectedSowing.dataset.fecha;

        const descripcion = `${especie} (${estanque}, ${zona}, ${fecha})`;

        // ✅ Doble confirmación
        Swal.fire({
            title: `¿Asignar la siembra de ${descripcion} a ${userName}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: '¿Estás completamente seguro?',
                    text: "Una vez asignado, no podrás revertirlo desde aquí.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, asignar',
                    cancelButtonText: 'Cancelar'
                }).then((result2) => {
                    if (result2.isConfirmed) {
                        document.getElementById('asignarForm').submit();
                    }
                });
            }
        });
    });

    @if(session('error'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: "{{ session('error') }}",
            showConfirmButton: false,
            timer: 3000
        });
    @endif

    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 3000
        });
    @endif
</script>
@endsection
