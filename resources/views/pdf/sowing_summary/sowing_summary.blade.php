<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        h1, h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background-color: #f1f1f1; }
    </style>
</head>
<body>
    <h1>Resumen de Siembra</h1>
    <p><strong>ID:</strong> {{ $sowing->id }}</p>
    <p><strong>Fecha de siembra:</strong> {{ $sowing->sowing_date }}</p>
    <p><strong>Fecha de finalización:</strong> {{ $sowing->sowing_completion ?? 'En curso' }}</p>
    <p><strong>Estanque:</strong> {{ $sowing->pond->name ?? 'N/D' }}</p>
    <p><strong>Identificador:</strong> {{ $sowing->identifier->identificador ?? 'N/D' }}</p>
    <p><strong>Especie:</strong> {{ $sowing->type->species->name ?? 'N/D' }}</p>
    <p><strong>Tipo:</strong> {{ $sowing->type->name ?? 'N/D' }}</p>
    <p><strong>Estado:</strong> {{ $sowing->state }}</p>

    <h2>🟩 Historial de Seguimiento De Dieta</h2>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Peso Prom.</th>
                <th>Balance Peces</th>
                <th>% Biomasa</th>
                <th>Biomasa</th>
                <th>Alimento Diario</th>
                <th># Raciones</th>
                <th>Ración</th>
                <th>Tipo Alimento</th>
                <th>Ganancia Peso</th>
                <th>Mortalidad Acum.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sowing->dietMonitorings as $d)
            <tr>
                <td>{{ $d->sampling_date }}</td>
                <td>{{ rtrim(rtrim(number_format($d->average_weight, 2, '.', ''), '0'), '.') }} g</td>
                <td>{{ $d->fish_balance }}</td>
                <td>{{ rtrim(rtrim(number_format($d->biomass_percentage, 2, '.', ''), '0'), '.') }} %</td>
                <td>{{ rtrim(rtrim(number_format($d->biomass, 2, '.', ''), '0'), '.') }} kg</td>
                <td>{{ rtrim(rtrim(number_format($d->daily_feed, 2, '.', ''), '0'), '.') }} kg</td>
                <td>{{ $d->ration_number }}</td>
                <td>{{ rtrim(rtrim(number_format($d->ration, 2, '.', ''), '0'), '.') }} g</td>
                <td>{{ $d->feed_type }}</td>
                <td>{{ rtrim(rtrim(number_format($d->weight_gain, 2, '.', ''), '0'), '.') }} g</td>
                <td>{{ rtrim(rtrim(number_format($d->cumulative_mortality, 2, '.', ''), '0'), '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>💧 Historial de Calidad del Agua</h2>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>pH</th>
                <th>Temp</th>
                <th>Amoniaco</th>
                <th>Turbidez</th>
                <th>Oxígeno</th>
                <th>Nitritos</th>
                <th>Nitratos</th>
                <th>Responsable</th>
                <th>Justificación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sowing->waterQualities as $wq)
            <tr>
                <td>{{ $wq->date }}</td>
                <td>{{ $wq->time }}</td>
                <td>{{ $wq->ph }}</td>
                <td>{{ $wq->temperature }}</td>
                <td>{{ $wq->ammonia }}</td>
                <td>{{ $wq->turbidity }}</td>
                <td>{{ $wq->dissolved_oxygen }}</td>
                <td>{{ $wq->nitrites }}</td>
                <td>{{ $wq->nitrates }}</td>
                <td>{{ $wq->responsible }}</td>
                <td>{{ $wq->justification }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>💀 Historial de Mortalidad</h2>
    <table>
        <thead>
            <tr>
                <th>Fecha y Hora</th>
                <th>Cantidad</th>
                <th>Balance Peces</th>
                <th>Observación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sowing->mortalities as $m)
            <tr>
                <td>{{ $m->datetime }}</td>
                <td>{{ $m->amount }}</td>
                <td>{{ $m->fish_balance }}</td>
                <td>{{ $m->observation }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>🍽️ Registros de Alimentación (Raciones)</h2>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>R1</th>
                <th>R2</th>
                <th>R3</th>
                <th>R4</th>
                <th>R5</th>
                <th>Ración Diaria</th>
                <th>Proteína Bruta</th>
                <th>Justificación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sowing->dietMonitorings as $monitoring)
                @foreach($monitoring->feedRecords as $fr)
                <tr>
                    <td>{{ $fr->feeding_date }}</td>
                    <td>{{ $fr->r1 }}</td>
                    <td>{{ $fr->r2 }}</td>
                    <td>{{ $fr->r3 }}</td>
                    <td>{{ $fr->r4 }}</td>
                    <td>{{ $fr->r5 }}</td>
                    <td>{{ $fr->daily_ration }}</td>
                    <td>{{ $fr->crude_protein }}</td>
                    <td>{{ $fr->justification }}</td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
