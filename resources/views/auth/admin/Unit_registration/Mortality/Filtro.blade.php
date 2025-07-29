@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Registro de Mortalidad por Estanque</h1>

    <a href="{{ route('mortality.create') }}" class="btn btn-primary mb-3">Registrar Nueva Mortalidad</a>

    @php
        $agrupadoPorEstanque = $filtro->groupBy(function ($item) {
            $nombre = $item->pondUnitCode->pond->name ?? 'Estanque sin nombre';
            $identificador = $item->pondUnitCode->identificador ?? 'Sin identificador';
            return $nombre . ' ' . $identificador;
        });
    @endphp

    {{-- SweetAlert de mensajes --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33'
            });
        </script>
    @endif

    <div class="accordion" id="estanqueAccordion">
        @foreach ($agrupadoPorEstanque as $estanqueNombreCompleto => $registros)
        @php
            $pondUnitCode = $registros->first()->pondUnitCode;
            $status = optional($pondUnitCode->lastSowing)->state ?? 'Sin estado';
            $estanqueSlug = Str::slug($estanqueNombreCompleto);
        @endphp
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-{{ $estanqueSlug }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse-{{ $estanqueSlug }}" aria-expanded="false"
                    aria-controls="collapse-{{ $estanqueSlug }}">
                    {{ $estanqueNombreCompleto }} - <span class="text-muted">{{ $status }}</span>
                </button>
            </h2>
            <div id="collapse-{{ $estanqueSlug }}" class="accordion-collapse collapse"
                aria-labelledby="heading-{{ $estanqueSlug }}"
                data-bs-parent="#estanqueAccordion">
                <div class="accordion-body p-0">

                    <div class="d-flex justify-content-end p-2">
                        <a href="{{ route('mortality.pdf.estanque', [
                            'pond_unit_code_id' => $pondUnitCode->id,
                            'sowing_id' => optional($pondUnitCode->lastSowing)->id
                        ]) }}" class="btn btn-danger btn-sm" target="_blank">
                            Descargar PDF Estanque Completo
                        </a>
                    </div>

                    <div class="accordion" id="registroAccordion-{{ $estanqueSlug }}">
                        @php $grupos = $registros->chunk(15); @endphp

                        @foreach ($grupos->reverse()->values() as $index => $grupo)
                        @php
                            $filaClass = count($grupo) == 15 ? 'table-warning' : 'table-success';
                            $quincenaNumero = $grupos->count() - $index;
                            $quincenaId = $estanqueSlug . '-q' . $quincenaNumero;
                        @endphp
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-{{ $quincenaId }}">
                                <button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse-{{ $quincenaId }}"
                                    aria-expanded="false"
                                    aria-controls="collapse-{{ $quincenaId }}">
                                    Quincena {{ $quincenaNumero }}
                                </button>
                            </h2>
                            <div id="collapse-{{ $quincenaId }}"
                                class="accordion-collapse collapse"
                                aria-labelledby="heading-{{ $quincenaId }}"
                                data-bs-parent="#registroAccordion-{{ $estanqueSlug }}">
                                <div class="accordion-body p-0">

                                    <div class="d-flex justify-content-end p-2">
                                        <a href="{{ route('mortality.pdf.quincena', [
                                            'pond_unit_code_id' => $pondUnitCode->id,
                                            'quincena' => $quincenaNumero
                                        ]) }}" class="btn btn-outline-danger btn-sm" target="_blank">
                                            Descargar PDF Quincena {{ $quincenaNumero }}
                                        </a>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped text-center align-middle m-0">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Fecha y Hora</th>
                                                    <th>Cantidad</th>
                                                    <th>Balance de Peces</th>
                                                    <th>Observación</th>
                                                    <th>Código de Estanque</th>
                                                    <th>Registrado por</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $contador = 1; @endphp
                                                @foreach ($grupo as $mortalidad)
                                                @php
                                                    $tiempoLimite = \Carbon\Carbon::parse($mortalidad->created_at)->addHours(24);
                                                @endphp
                                                <tr class="{{ $filaClass }}">
                                                    <td>{{ $contador++ }}</td>
                                                    <td>{{ $mortalidad->datetime }}</td>
                                                    <td>{{ $mortalidad->amount }}</td>
                                                    <td>{{ $mortalidad->fish_balance }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#observacionModal{{ $mortalidad->id }}">
                                                            <i class="fas fa-book"></i>
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="observacionModal{{ $mortalidad->id }}" tabindex="-1" aria-labelledby="observacionLabel{{ $mortalidad->id }}" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="observacionLabel{{ $mortalidad->id }}">Observación completa</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                                    </div>
                                                                    <div class="modal-body text-start">
                                                                        {{ $mortalidad->observation ?? 'Sin observación registrada.' }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {{ $mortalidad->pondUnitCode->pond->name ?? 'Sin nombre' }} -
                                                        {{ $mortalidad->pondUnitCode->identificador ?? 'Sin identificador' }}
                                                    </td>
                                                    <td class="text-truncate" style="max-width: 300px;">
                                                        {{ $mortalidad->user->name ?? 'Sin nombre' }}
                                                        {{ $mortalidad->user->last_name ?? 'Sin apellido' }}
                                                        - {{ $mortalidad->user->document ?? 'Sin cédula' }}
                                                    </td>
                                                    <td>
                                                        @if ($now < $tiempoLimite)
                                                            <a href="{{ route('mortality.edit', $mortalidad->id) }}" class="btn btn-sm btn-warning">Editar</a>

                                                            <form id="delete-form-{{ $mortalidad->id }}" action="{{ route('mortality.destroy', $mortalidad->id) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmarDobleEliminacion({{ $mortalidad->id }})">
                                                                    Eliminar
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="text-muted">Expirado</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    .table th,
    .table td {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmarDobleEliminacion(id) {
        Swal.fire({
            title: '¿Deseas eliminar este registro?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar'
        }).then((primerResultado) => {
            if (primerResultado.isConfirmed) {
                Swal.fire({
                    title: '¿Estás completamente seguro?',
                    text: 'Una vez eliminado, no podrás recuperarlo.',
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#b02a37',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar definitivamente',
                    cancelButtonText: 'Cancelar'
                }).then((segundoResultado) => {
                    if (segundoResultado.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            }
        });
    }
</script>
@endsection
