<h1>bienvenido administrador</h1>

<form action= "{{ route('logout') }}" method="post">
    @csrf
    <button type="submit">cerrar seccion</button>
</form>
