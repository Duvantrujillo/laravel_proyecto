<!DOCTYPE html>
<html>
<head>
    <title>Reporte Mortalidad - Quincena {{ $quincena }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        .encabezado { margin-bottom: 20px; }
    </style>
</head>
<body>

    <h2>Reporte de Mortalidad - Quincena {{ $quincena }}</h2>

    <div class="encabezado">
        <strong>Nombre del Estanque:</strong> {{ $pond->pond->name ?? 'Sin nombre' }}<br>
        <strong>Identificador:</strong> {{ $pond->identificador ?? 'Sin identificador' }}<br>
        <strong>Estado de siembra:</strong> {{ optional($pond->lastSowing)->state ?? 'Sin estado' }}<br>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha y Hora</th>
                <th>Cantidad</th>
                <th>Balance de Peces</th>
                <th>Observación</th>
                <th>Registrado por</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($grupo as $i => $r)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $r->datetime }}</td>
                    <td>{{ $r->amount }}</td>
                    <td>{{ $r->fish_balance }}</td>
                    <td>{{ $r->observation }}</td>
                    <td>
                        {{ $r->user->name ?? 'Sin nombre' }}
                        {{ $r->user->last_name ?? '' }}<br>
                        {{ $r->user->document ?? 'Sin cédula' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
