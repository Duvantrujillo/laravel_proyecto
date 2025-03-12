@extends('layouts.master')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Registrar nuevo grupo</h1>
    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif
    <form action="{{route('grupos.store')}}" method="post">
        @csrf
        <label for="">nombre del grupo</label>
        <input type="text" name="nombre" id="">
        <label for="">Numero de ficha</label>
        <input type="text" name="ficha" id="">

        <button type="submit">Registrar grupo</button>
    </form>
</body>
</html>
@endsection