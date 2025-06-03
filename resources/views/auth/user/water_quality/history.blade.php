@extends('layouts.master')

@section('content')
    <div style="padding: 20px;">
        <h2>Historial de Calidad de Agua </h2>

        @if ($waterQualities->isEmpty())
            <p>No hay registros de calidad de agua para esta siembra.</p>
        @else
            <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>pH</th>
                        <th>Temperatura</th>
                        <th>Amoníaco</th>
                        <th>Turbidez</th>
                        <th>Oxígeno Disuelto</th>
                        <th>Nitritos</th>
                        <th>Nitratos</th>
                        <th>Usuario Responsable (ID)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($waterQualities as $quality)
                        <tr>
                            <td>{{ $quality->date }}</td>
                            <td>{{ $quality->time }}</td>
                            <td>{{ $quality->ph == (int) $quality->ph ? number_format($quality->ph, 0) : number_format($quality->ph, 2) }}</td>
                            <td>{{ $quality->temperature == (int) $quality->temperature ? number_format($quality->temperature, 0) : number_format($quality->temperature, 2) }}</td>
                            <td>{{ $quality->ammonia == (int) $quality->ammonia ? number_format($quality->ammonia, 0) : number_format($quality->ammonia, 2) }}</td>
                            <td>{{ $quality->turbidity == (int) $quality->turbidity ? number_format($quality->turbidity, 0) : number_format($quality->turbidity, 2) }}</td>
                            <td>{{ $quality->dissolved_oxygen == (int) $quality->dissolved_oxygen ? number_format($quality->dissolved_oxygen, 0) : number_format($quality->dissolved_oxygen, 2) }}</td>
                            <td>{{ $quality->nitrites == (int) $quality->nitrites ? number_format($quality->nitrites, 0) : number_format($quality->nitrites, 2) }}</td>
                            <td>{{ $quality->nitrates == (int) $quality->nitrates ? number_format($quality->nitrates, 0) : number_format($quality->nitrates, 2) }}</td>
                            <td>{{ $quality->user->email ?? 'Sin usuario' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <a href="{{ url()->previous() }}" style="margin-top: 20px; display: inline-block;">Volver</a>
    </div>
@endsection
