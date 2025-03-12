@extends('layouts.master')

@section('content')


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario</title>
</head>
<body>
    <form action="{{route('register.store')}}" method="POST">
        @csrf
        <label for="grupo">Seleccione un grupo</label>
        <select name="grupo" id="grupo" class="form-control" required>
            <option value="">Seleccione el Grupo</option>
            @foreach($grupos as $items)
                <option value="{{ $items->id }}">{{ $items->nombre }} ({{ $items->numero_ficha }})</option>
            @endforeach
        </select>
        

        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre">

        <label for="numero_documento">Número de Documento</label>
        <input type="text" name="numero_documento" id="numero_documento">

        <label for="numero_telefono">Número de teléfono</label>
        <input type="tel" name="numero_telefono" id="numero_telefono">

        <label for="correo">Correo</label>
        <input type="email" name="correo" id="correo">

        

        <button type="submit">Enviar</button>
    </form>
</body>
</html>



@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


@endsection