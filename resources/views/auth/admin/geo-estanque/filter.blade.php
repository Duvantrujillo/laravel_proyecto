<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table>
        <theader>
            <tr>
                <th>nombre</th>
                <th>identificador</th>
            </tr>
            @foreach ($filtros as $item)
            <tr>
                <td>{{ $item->name}}</td>
                <td>{{$item->identifier}}
            </tr>
                
            @endforeach
        </theader>
    </table>

</body>
</html>