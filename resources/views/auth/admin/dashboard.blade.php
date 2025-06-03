<h1>bienvenido Usuario</h1>

<form action= "{{ route('logout') }}" method="post">
    @csrf
    <button type="submit">cerrar seccion</button>
</form>
