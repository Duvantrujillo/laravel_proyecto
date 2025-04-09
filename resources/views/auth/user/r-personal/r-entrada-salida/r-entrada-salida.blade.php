@extends('layouts.master')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card card-outline card-primary elevation-2" style="border-top: 3px solid #4b5e82;">
                    <div class="card-header bg-transparent border-0">
                        <h3 class="card-title text-dark"><i class="fas fa-door-open mr-2"></i> Registrar Salida</h3>
                    </div>
                    <div class="card-body p-4">
                        <form id="entradaSalidaForm" action="{{ route('entrada_salida.store') }}" method="POST">
                            @csrf
                            <div class="row mb-4">
                                <!-- Selección del Grupo -->
                                <div class="col-md-6">
                                    <label for="grupo" class="d-block text-dark">Grupo</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light"><i class="fas fa-users"></i></span>
                                        </div>
                                        <select name="grupo" id="grupo" class="form-control @error('grupo') is-invalid @enderror" required>
                                            <option value="">Seleccione un grupo</option>
                                            @foreach($grupos as $items)
                                                <option value="{{ $items->id }}">{{ $items->nombre }} ({{ $items->numero_ficha }})</option>
                                            @endforeach
                                        </select>
                                        @error('grupo')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Selección de la Ficha -->
                                <div class="col-md-6">
                                    <label for="ficha" class="d-block text-dark">Ficha</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light"><i class="fas fa-id-badge"></i></span>
                                        </div>
                                        <select name="ficha" id="ficha" class="form-control @error('ficha') is-invalid @enderror" required>
                                            <option value="">Seleccione una ficha</option>
                                        </select>
                                        @error('ficha')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Controles globales -->
                            <div class="row mb-3 align-items-end">
                                <div class="col-md-6">
                                    <label for="fechaEntradaGlobal" class="d-block text-dark">Fecha Entrada Global</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="datetime-local" id="fechaEntradaGlobal" class="form-control" value="{{ now()->toDateTimeLocalString() }}">
                                    </div>
                                    <button type="button" id="aplicarFechaGlobal" class="btn btn-outline-primary btn-block mt-2"><i class="fas fa-clock mr-1"></i> Aplicar Fecha</button>
                                </div>
                                <div class="col-md-6">
                                    <label for="visitoGlobal" class="d-block text-dark">Visitó Granja Global</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light"><i class="fas fa-tractor"></i></span>
                                        </div>
                                        <select id="visitoGlobal" class="form-control">
                                            <option value="0">No</option>
                                            <option value="1">Sí</option>
                                        </select>
                                    </div>
                                    <button type="button" id="aplicarVisitoGlobal" class="btn btn-outline-primary btn-block mt-2"><i class="fas fa-check mr-1"></i> Aplicar Visitó</button>
                                </div>
                            </div>

                            <!-- Botones de selección masiva -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <button type="button" id="seleccionarTodosBtn" class="btn btn-outline-info btn-block"><i class="fas fa-users mr-1"></i> Seleccionar Todos</button>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" id="seleccionarTodasFechasBtn" class="btn btn-outline-info btn-block"><i class="fas fa-calendar-check mr-1"></i> Sel. Todas Fechas</button>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" id="seleccionarTodosVisitoBtn" class="btn btn-outline-info btn-block"><i class="fas fa-tractor mr-1"></i> Sel. Todos Visitó</button>
                                </div>
                            </div>

                            <!-- Tabla de Usuarios -->
                            <div class="table-responsive mb-4">
                                <table class="table table-hover table-bordered" id="usuariosTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">
                                                <input type="checkbox" id="seleccionarTodosUsuarios" title="Seleccionar todos los usuarios">
                                            </th>
                                            <th>Nombre</th>
                                            <th class="text-center">
                                                Fecha Entrada
                                                <input type="checkbox" id="seleccionarTodasFechas" title="Seleccionar todas las fechas">
                                            </th>
                                            <th class="text-center">
                                                Visitó Granja
                                                <input type="checkbox" id="seleccionarTodosVisito" title="Seleccionar todos los visitó">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="usuariosBody">
                                        <!-- Los usuarios se cargarán dinámicamente aquí -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Botón de envío -->
                            <button type="submit" class="btn btn-primary btn-block" style="background: #4b5e82; border: none;"><i class="fas fa-save mr-1"></i> Registrar Seleccionados</button>
                        </form>

                        <!-- Mensajes -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible mt-3 fade show" role="alert">
                                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        @endif
                        @if($errors->any() && !session('success'))
                            <div class="alert alert-danger alert-dismissible mt-3 fade show" role="alert">
                                <i class="fas fa-exclamation-circle mr-2"></i> Por favor, corrige los errores.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<style>
    /* Hacer las celdas clickeables */
    .checkbox-cell {
        cursor: pointer;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const grupoSelect = document.getElementById('grupo');
        const fichaSelect = document.getElementById('ficha');
        const usuariosBody = document.getElementById('usuariosBody');
        const fechaEntradaGlobal = document.getElementById('fechaEntradaGlobal');
        const aplicarFechaGlobal = document.getElementById('aplicarFechaGlobal');
        const visitoGlobal = document.getElementById('visitoGlobal');
        const aplicarVisitoGlobal = document.getElementById('aplicarVisitoGlobal');
        const seleccionarTodosUsuarios = document.getElementById('seleccionarTodosUsuarios');
        const seleccionarTodasFechas = document.getElementById('seleccionarTodasFechas');
        const seleccionarTodosVisito = document.getElementById('seleccionarTodosVisito');
        const seleccionarTodosBtn = document.getElementById('seleccionarTodosBtn');
        const seleccionarTodosBtnText = seleccionarTodosBtn.querySelector('span') || seleccionarTodosBtn; // Si no hay span, usa el botón directamente
        const seleccionarTodasFechasBtn = document.getElementById('seleccionarTodasFechasBtn');
        const seleccionarTodasFechasBtnText = seleccionarTodasFechasBtn.querySelector('span') || seleccionarTodasFechasBtn;
        const seleccionarTodosVisitoBtn = document.getElementById('seleccionarTodosVisitoBtn');
        const seleccionarTodosVisitoBtnText = seleccionarTodosVisitoBtn.querySelector('span') || seleccionarTodosVisitoBtn;

        // Cargar fichas según el grupo seleccionado
        grupoSelect.addEventListener('change', function () {
            const grupoId = this.value;
            fichaSelect.innerHTML = '<option value="">Seleccione una ficha</option>';
            usuariosBody.innerHTML = '';

            if (grupoId) {
                fetch(`/get-fichas-por-grupo?grupo=${grupoId}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Error al cargar las fichas');
                        return response.json();
                    })
                    .then(data => {
                        data.forEach(ficha => {
                            const option = document.createElement('option');
                            option.value = ficha.id;
                            option.textContent = ficha.nombre;
                            fichaSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Hubo un error al cargar las fichas.');
                    });
            }
        });

        // Cargar usuarios en la tabla según la ficha seleccionada
        fichaSelect.addEventListener('change', function () {
            const fichaId = this.value;
            usuariosBody.innerHTML = '';

            if (fichaId) {
                fetch(`/get-usuarios-por-ficha?ficha=${fichaId}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Error al cargar los usuarios');
                        return response.json();
                    })
                    .then(data => {
                        data.forEach(usuario => {
                            const row = document.createElement('tr');
                            const now = new Date();
                            const fechaHoraLocal = now.toISOString().slice(0, 16);

                            row.innerHTML = `
                                <td class="text-center checkbox-cell" data-checkbox-type="selected">
                                    <input type="checkbox" name="usuarios[${usuario.id}][selected]" value="1">
                                </td>
                                <td class="checkbox-cell" data-checkbox-type="none">${usuario.nombre}</td>
                                <td class="text-center checkbox-cell" data-checkbox-type="fecha_selected">
                                    <div class="input-group">
                                        <input type="checkbox" name="usuarios[${usuario.id}][fecha_selected]" value="1" class="mr-2">
                                        <input type="datetime-local" name="usuarios[${usuario.id}][entrada]" value="${fechaHoraLocal}" class="form-control" disabled>
                                    </div>
                                </td>
                                <td class="text-center checkbox-cell" data-checkbox-type="visito_selected">
                                    <div class="input-group">
                                        <input type="checkbox" name="usuarios[${usuario.id}][visito_selected]" value="1" class="mr-2">
                                        <select name="usuarios[${usuario.id}][visito_granja]" class="form-control" disabled>
                                            <option value="0">No</option>
                                            <option value="1">Sí</option>
                                        </select>
                                    </div>
                                </td>
                            `;
                            usuariosBody.appendChild(row);

                            // Habilitar/deshabilitar campos según checkboxes individuales
                            const selectedCheckbox = row.querySelector('input[name$="[selected]"]');
                            const fechaCheckbox = row.querySelector('input[name$="[fecha_selected]"]');
                            const visitoCheckbox = row.querySelector('input[name$="[visito_selected]"]');
                            const entradaInput = row.querySelector('input[type="datetime-local"]');
                            const visitoSelect = row.querySelector('select');

                            selectedCheckbox.addEventListener('change', function () {
                                const isChecked = this.checked;
                                entradaInput.disabled = !isChecked || !fechaCheckbox.checked;
                                visitoSelect.disabled = !isChecked || !visitoCheckbox.checked;
                            });

                            fechaCheckbox.addEventListener('change', function () {
                                entradaInput.disabled = !selectedCheckbox.checked || !this.checked;
                            });

                            visitoCheckbox.addEventListener('change', function () {
                                visitoSelect.disabled = !selectedCheckbox.checked || !this.checked;
                            });
                        });

                        // Añadir eventos a las celdas después de cargar las filas
                        addCheckboxCellEvents();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Hubo un error al cargar los usuarios.');
                    });
            }
        });

        // Seleccionar todos los usuarios (desde el encabezado)
        seleccionarTodosUsuarios.addEventListener('change', function () {
            const allCheckboxes = document.querySelectorAll('input[name$="[selected]"]');
            allCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                const row = checkbox.closest('tr');
                const fechaCheckbox = row.querySelector('input[name$="[fecha_selected]"]');
                const visitoCheckbox = row.querySelector('input[name$="[visito_selected]"]');
                const entradaInput = row.querySelector('input[type="datetime-local"]');
                const visitoSelect = row.querySelector('select');
                entradaInput.disabled = !this.checked || !fechaCheckbox.checked;
                visitoSelect.disabled = !this.checked || !visitoCheckbox.checked;
            });
            seleccionarTodosBtnText.innerHTML = this.checked ? '<i class="fas fa-users mr-1"></i> Deseleccionar Todos' : '<i class="fas fa-users mr-1"></i> Seleccionar Todos';
        });

        // Seleccionar todas las fechas (desde el encabezado)
        seleccionarTodasFechas.addEventListener('change', function () {
            const allFechaCheckboxes = document.querySelectorAll('input[name$="[fecha_selected]"]');
            allFechaCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                const row = checkbox.closest('tr');
                const selectedCheckbox = row.querySelector('input[name$="[selected]"]');
                const entradaInput = row.querySelector('input[type="datetime-local"]');
                entradaInput.disabled = !selectedCheckbox.checked || !this.checked;
            });
            seleccionarTodasFechasBtnText.innerHTML = this.checked ? '<i class="fas fa-calendar-check mr-1"></i> Desel. Todas Fechas' : '<i class="fas fa-calendar-check mr-1"></i> Sel. Todas Fechas';
        });

        // Seleccionar todos los "Visitó granja" (desde el encabezado)
        seleccionarTodosVisito.addEventListener('change', function () {
            const allVisitoCheckboxes = document.querySelectorAll('input[name$="[visito_selected]"]');
            allVisitoCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                const row = checkbox.closest('tr');
                const selectedCheckbox = row.querySelector('input[name$="[selected]"]');
                const visitoSelect = row.querySelector('select');
                visitoSelect.disabled = !selectedCheckbox.checked || !this.checked;
            });
            seleccionarTodosVisitoBtnText.innerHTML = this.checked ? '<i class="fas fa-tractor mr-1"></i> Desel. Todos Visitó' : '<i class="fas fa-tractor mr-1"></i> Sel. Todos Visitó';
        });

        // Botón "Seleccionar/Deseleccionar Todos" (usuarios)
        seleccionarTodosBtn.addEventListener('click', function () {
            const allCheckboxes = document.querySelectorAll('input[name$="[selected]"]');
            const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
            allCheckboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
                const row = checkbox.closest('tr');
                const fechaCheckbox = row.querySelector('input[name$="[fecha_selected]"]');
                const visitoCheckbox = row.querySelector('input[name$="[visito_selected]"]');
                const entradaInput = row.querySelector('input[type="datetime-local"]');
                const visitoSelect = row.querySelector('select');
                entradaInput.disabled = !checkbox.checked || !fechaCheckbox.checked;
                visitoSelect.disabled = !checkbox.checked || !visitoCheckbox.checked;
            });
            seleccionarTodosUsuarios.checked = !allChecked;
            seleccionarTodosBtn.innerHTML = !allChecked ? '<i class="fas fa-users mr-1"></i> Deseleccionar Todos' : '<i class="fas fa-users mr-1"></i> Seleccionar Todos';
        });

        // Botón "Seleccionar/Deseleccionar Todas Fechas"
        seleccionarTodasFechasBtn.addEventListener('click', function () {
            const allFechaCheckboxes = document.querySelectorAll('input[name$="[fecha_selected]"]');
            const allChecked = Array.from(allFechaCheckboxes).every(cb => cb.checked);
            allFechaCheckboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
                const row = checkbox.closest('tr');
                const selectedCheckbox = row.querySelector('input[name$="[selected]"]');
                const entradaInput = row.querySelector('input[type="datetime-local"]');
                entradaInput.disabled = !selectedCheckbox.checked || !checkbox.checked;
            });
            seleccionarTodasFechas.checked = !allChecked;
            seleccionarTodasFechasBtn.innerHTML = !allChecked ? '<i class="fas fa-calendar-check mr-1"></i> Desel. Todas Fechas' : '<i class="fas fa-calendar-check mr-1"></i> Sel. Todas Fechas';
        });

        // Botón "Seleccionar/Deseleccionar Todos Visitó"
        seleccionarTodosVisitoBtn.addEventListener('click', function () {
            const allVisitoCheckboxes = document.querySelectorAll('input[name$="[visito_selected]"]');
            const allChecked = Array.from(allVisitoCheckboxes).every(cb => cb.checked);
            allVisitoCheckboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
                const row = checkbox.closest('tr');
                const selectedCheckbox = row.querySelector('input[name$="[selected]"]');
                const visitoSelect = row.querySelector('select');
                visitoSelect.disabled = !selectedCheckbox.checked || !checkbox.checked;
            });
            seleccionarTodosVisito.checked = !allChecked;
            seleccionarTodosVisitoBtn.innerHTML = !allChecked ? '<i class="fas fa-tractor mr-1"></i> Desel. Todos Visitó' : '<i class="fas fa-tractor mr-1"></i> Sel. Todos Visitó';
        });

        // Aplicar fecha global a las fechas seleccionadas
        aplicarFechaGlobal.addEventListener('click', function () {
            const allEntradas = document.querySelectorAll('input[type="datetime-local"]:not(#fechaEntradaGlobal)');
            const fechaGlobal = fechaEntradaGlobal.value;

            allEntradas.forEach(entrada => {
                if (!entrada.disabled) {
                    entrada.value = fechaGlobal;
                }
            });
        });

        // Aplicar "Visitó granja" global a los seleccionados
        aplicarVisitoGlobal.addEventListener('click', function () {
            const allVisitos = document.querySelectorAll('select[name$="[visito_granja]"]');
            const visitoValue = visitoGlobal.value;

            allVisitos.forEach(visito => {
                if (!visito.disabled) {
                    visito.value = visitoValue;
                }
            });
        });

        // Función para añadir eventos a las celdas clickeables
        function addCheckboxCellEvents() {
            const cells = document.querySelectorAll('.checkbox-cell');
            cells.forEach(cell => {
                cell.addEventListener('click', function (e) {
                    // Evitar que el clic en el checkbox o en inputs/select dispare el evento dos veces
                    if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'SELECT') {
                        const checkboxType = this.getAttribute('data-checkbox-type');
                        if (checkboxType && checkboxType !== 'none') {
                            const checkbox = this.querySelector(`input[name$="[${checkboxType}]"]`);
                            if (checkbox) {
                                checkbox.checked = !checkbox.checked;
                                // Disparar el evento 'change' manualmente para actualizar los campos habilitados/deshabilitados
                                checkbox.dispatchEvent(new Event('change'));
                            }
                        }
                    }
                });
            });
        }
    });
</script>
@endsection