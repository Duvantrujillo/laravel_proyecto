@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Registro de Mortalidad por Estanque</h1>

        {{-- Botón para redirigir a otra vista --}}
        <a href="{{ route('mortality.create') }}" class="btn btn-primary mb-3">Registrar Nueva Mortalidad</a>

        @php
            // Agrupamos por nombre + identificador de estanque para diferenciarlos correctamente
            $agrupadoPorEstanque = $filtro->groupBy(function ($item) {
                $nombre = $item->pondUnitCode->pond->name ?? 'Estanque sin nombre';
                $identificador = $item->pondUnitCode->identificador ?? 'Sin identificador';
                return $nombre . ' ' . $identificador;
            });
        @endphp

        <div class="accordion" id="estanqueAccordion">
            @foreach ($agrupadoPorEstanque as $estanqueNombreCompleto => $registros)
                @php
                    // Obtener el pond_unit_code del primer registro
                    $pondUnitCode = $registros->first()->pondUnitCode;

                    // Obtener el estado de la última siembra directamente
                    $status = optional($pondUnitCode->lastSowing)->state ?? 'Sin estado';
                @endphp
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-{{ Str::slug($estanqueNombreCompleto) }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse-{{ Str::slug($estanqueNombreCompleto) }}" aria-expanded="false"
                            aria-controls="collapse-{{ Str::slug($estanqueNombreCompleto) }}">
                            {{ $estanqueNombreCompleto }} - <span class="text-muted">{{ $status }}</span>
                        </button>
                    </h2>
                    <div id="collapse-{{ Str::slug($estanqueNombreCompleto) }}" class="accordion-collapse collapse"
                        aria-labelledby="heading-{{ Str::slug($estanqueNombreCompleto) }}"
                        data-bs-parent="#estanqueAccordion">
                        <div class="accordion-body p-0">

                            {{-- Sub-acordeón para los registros divididos en grupos de 15 --}}
                            <div class="accordion" id="registroAccordion-{{ Str::slug($estanqueNombreCompleto) }}">
                                @foreach ($registros->chunk(15) as $index => $grupo)
                                    @php
                                        $filaClass = count($grupo) == 15 ? 'table-warning' : 'table-success';
                                    @endphp
                                    <div class="accordion-item">
                                        <h2 class="accordion-header"
                                            id="heading-{{ Str::slug($estanqueNombreCompleto) }}-{{ $index }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapse-{{ Str::slug($estanqueNombreCompleto) }}-{{ $index }}"
                                                aria-expanded="false"
                                                aria-controls="collapse-{{ Str::slug($estanqueNombreCompleto) }}-{{ $index }}">
                                                Quincena {{ $index + 1 }}
                                            </button>
                                        </h2>
                                        <div id="collapse-{{ Str::slug($estanqueNombreCompleto) }}-{{ $index }}"
                                            class="accordion-collapse collapse"
                                            aria-labelledby="heading-{{ Str::slug($estanqueNombreCompleto) }}-{{ $index }}"
                                            data-bs-parent="#registroAccordion-{{ Str::slug($estanqueNombreCompleto) }}">
                                            <div class="accordion-body p-0">
                                                <div class="table-responsive">
                                                    <table
                                                        class="table table-bordered table-striped text-center align-middle m-0">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Fecha y Hora</th>
                                                                <th>Cantidad</th>
                                                                <th>Balance de Peces</th>
                                                                <th>Observación</th>
                                                                <th>Código de Estanque</th>
                                                                <th>Registrado por</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $contador = 1; @endphp
                                                            @foreach ($grupo as $mortalidad)
                                                                <tr class="{{ $filaClass }}">
                                                                    <td>{{ $contador }}</td>
                                                                    <td>{{ $mortalidad->datetime }}</td>
                                                                    <td>{{ $mortalidad->amount }}</td>
                                                                    <td>{{ $mortalidad->fish_balance }}</td>
                                                                    <td class="text-truncate" style="max-width: 200px;">
                                                                        {{ $mortalidad->observation }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $mortalidad->pondUnitCode->pond->name ?? 'Sin nombre de estanque' }}
                                                                        -
                                                                        {{ $mortalidad->pondUnitCode->identificador ?? 'Sin identificador' }}
                                                                    </td>
                                                                    <td class="text-truncate" style="max-width: 300px;">
                                                                        {{ $mortalidad->user->name ?? 'Sin nombre' }}
                                                                        {{ $mortalidad->user->last_name ?? 'Sin apellido' }}
                                                                        - {{ $mortalidad->user->document ?? 'Sin cédula' }}
                                                                    </td>
                                                                </tr>
                                                                @php $contador++; @endphp
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
    <style>
        .table th,
        .table td {
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }
    </style>
@endsection
