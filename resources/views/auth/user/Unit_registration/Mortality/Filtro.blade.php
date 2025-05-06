@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Registro de Mortalidad</h1>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Fecha y Hora</th>
                    <th>Cantidad</th>
                    <th>Balance de Peces</th>
                    <th>Observación</th>
                    <th>Código de Estanque</th>
                    <th>Registrado por</th>
                </tr>
            </thead>
            <tbody>
                @foreach($filtro as $mortalidad)
                <tr>
                    <td>{{ $mortalidad->datetime }}</td>
                    <td>{{ $mortalidad->amount }}</td>
                    <td>{{ $mortalidad->fish_balance }}</td>
                    <td class="text-truncate" style="max-width: 200px;">{{ $mortalidad->observation }}</td>
                    
                    <!-- Mostrar nombre del estanque -->
                    <td>
                        {{ $mortalidad->pondUnitCode->pond->name ?? 'Sin nombre de estanque' }} 
                        - {{ $mortalidad->pondUnitCode->identificador ?? 'Sin identificador' }}
                    </td>

                    <td class="text-truncate" style="max-width: 300px;">
                        <!-- Mostrar nombre y cédula del usuario -->
                        {{ $mortalidad->user->name ?? 'Sin nombre' }} 
                        {{ $mortalidad->user->last_name ?? 'Sin apellido' }} 
                        - {{ $mortalidad->user->document ?? 'Sin cédula' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@section('styles')
<style>
    .table th, .table td {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }
</style>
@endsection

@endsection
