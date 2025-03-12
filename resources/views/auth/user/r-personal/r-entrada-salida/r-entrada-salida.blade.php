@extends('layouts.master')

@section('content')

<form id="entradaSalidaForm" action="{{ route('entrada_salida.store') }}" method="POST">
    @csrf    
    <!-- Selección del Grupo -->
    <label for="grupo">Grupo:</label>
    <select name="grupo" id="grupo" class="form-control" required>
        <option value="">Seleccione el Grupo</option>
        @foreach($grupos as $items)
            <option value="{{ $items->id }}">{{ $items->nombre }} ({{ $items->numero_ficha }})</option>
        @endforeach
    </select>

    <!-- Selección del Usuario (se carga según el grupo seleccionado) -->
    <label for="usuario">Usuario:</label>
    <select name="usuario" id="usuario" required>
        <option value="">Seleccione un usuario</option>
    </select>

    <!-- Fecha y Hora de Entrada -->
    <label for="entrada">Fecha y Hora de Entrada:</label>
    <input type="datetime-local" name="entrada" required>

    <!-- Fecha y Hora de Salida -->
    <label for="salida">Fecha y Hora de Salida:</label>
    <input type="datetime-local" name="salida" required>

    <label for="">¿Visitó otra granja en las últimas 48 horas?</label>
    <select name="visitó_granja" id="">
        <option value="1">Sí</option>
        <option value="0">No</option>
    </select>

    <button type="submit">Registrar Entrada/Salida</button>
</form>

<script>
// JavaScript para cargar los usuarios del grupo seleccionado
document.getElementById('grupo').addEventListener('change', function() {
    var grupoId = this.value;

    // Verificar si se seleccionó un grupo
    if (grupoId) {
        // Hacer una solicitud AJAX para obtener los usuarios por grupo
        fetch(`/get-usuarios-por-grupo?grupo=${grupoId}`)
            .then(response => response.json())
            .then(data => {
                var usuarioSelect = document.getElementById('usuario');
                usuarioSelect.innerHTML = '<option value="">Seleccione un usuario</option>'; // Limpiar el select

                // Llenar el select con los usuarios
                data.forEach(usuario => {
                    let option = document.createElement('option');
                    option.value = usuario.id;
                    option.textContent = usuario.nombre;
                    usuarioSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error:', error));
    }
});
</script>

@endsection
