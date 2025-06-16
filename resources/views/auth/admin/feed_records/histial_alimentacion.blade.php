@extends('layouts.master')

@section('content')
<style>
    .history-container {
        padding: 20px;
        max-width: 100%;
        overflow-x: auto;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 14px;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
        vertical-align: middle;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .no-data {
        text-align: center;
        color: #666;
        font-style: italic;
    }

    .btn {
        padding: 4px 8px;
        margin: 0 2px;
        font-size: 13px;
        cursor: pointer;
        border: none;
        border-radius: 4px;
    }

    .btn-edit {
        background-color: #4CAF50;
        color: white;
    }

    .btn-delete {
        background-color: #f44336;
        color: white;
    }

    .edit-form {
        display: none;
    }

    .edit-input,
    .edit-textarea {
        width: 100px;
        padding: 4px;
        box-sizing: border-box;
        text-align: center;
    }

    .edit-textarea {
        width: 100%;
        min-height: 50px;
        resize: vertical;
        text-align: left;
    }

    .edit-input-date {
        width: 120px;
    }

    details summary {
        font-weight: bold;
        cursor: pointer;
        margin: 10px 0;
        background-color: #f9f9f9;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
</style>

@php
function formatDateSafe($date, $format = 'd/m/Y') {
if ($date instanceof \Illuminate\Support\Carbon || $date instanceof \DateTime) {
return $date->format($format);
}
return '-';
}

function formatNumberCol($number) {
if ($number === null) return '-';

if (floor($number) == $number) {
return number_format($number, 0, ',', '.');
} else {
return number_format($number, 2, ',', '.');
}
}

function formatJustificationNumbers($text) {
if (!$text) return '-';
return preg_replace_callback('/\d+(\.\d+)?/', function($matches) {
$num = $matches[0];
$numFloat = floatval($num);
if (floor($numFloat) == $numFloat) {
return number_format($numFloat, 0, ',', '.');
} else {
return number_format($numFloat, 2, ',', '.');
}
}, $text);
}
@endphp

<div class="history-container">
    <h2>Historial de Alimentación - {{ $sowing->pond->name ?? 'No disponible' }}</h2>

    @if ($feedRecords->isEmpty())
    <p class="no-data">No hay registros de alimentación para esta siembra.</p>
    @else
    @php
    $chunkSize = 15;
    $totalChunks = ceil($feedRecords->count() / $chunkSize);
    @endphp

    @foreach ($feedRecords->chunk($chunkSize) as $index => $chunk)
    <details>
        <summary>Mostrar registros {{ ($index * $chunkSize + 1) }} a {{ min(($index + 1) * $chunkSize, $feedRecords->count()) }}</summary>
        <table>
            <thead>
                <tr>
                    <th>Fecha de Alimentación</th>
                    <th>Ración 1 (g)</th>
                    <th>Ración 2 (g)</th>
                    <th>Ración 3 (g)</th>
                    <th>Ración 4 (g)</th>
                    <th>Ración 5 (g)</th>
                    <th>Ración Total (g)</th>
                    <th>Proteína Cruda (%)</th>
                    <th>Justificación</th>
                    <th>Fecha Registro</th>
                    <th>Responsable</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($chunk as $record)
                <tr id="record-row-{{ $record->id }}">
                    <td>
                        <span class="display-field" id="feeding_date_display_{{ $record->id }}">{{ formatDateSafe($record->feeding_date) }}</span>
                    </td>
                    <td><span class="display-field" id="r1_display_{{ $record->id }}">{{ formatNumberCol($record->r1) }}</span></td>
                    <td><span class="display-field" id="r2_display_{{ $record->id }}">{{ formatNumberCol($record->r2) }}</span></td>
                    <td><span class="display-field" id="r3_display_{{ $record->id }}">{{ formatNumberCol($record->r3) }}</span></td>
                    <td><span class="display-field" id="r4_display_{{ $record->id }}">{{ formatNumberCol($record->r4) }}</span></td>
                    <td><span class="display-field" id="r5_display_{{ $record->id }}">{{ formatNumberCol($record->r5) }}</span></td>
                    <td><span class="display-field" id="daily_ration_display_{{ $record->id }}">{{ formatNumberCol($record->daily_ration) }}</span></td>
                    <td><span class="display-field" id="crude_protein_display_{{ $record->id }}">{{ formatNumberCol($record->crude_protein) }}</span></td>
                    <td>
                        <span class="display-field" id="justification_display_{{ $record->id }}">{!! nl2br(e(formatJustificationNumbers($record->justification))) !!}</span>
                    </td>
                    <td>{{ formatDateSafe($record->created_at, 'd/m/Y H:i') }}</td>
                    <td>{{ $record->responsible ? $record->responsible->name : 'No disponible' }}</td>
                    <td>
                        <a href="{{ route('feedRecords.edit', $record->id) }}" class="btn btn-edit">Editar</a>

                        @auth
                        @if (Auth::user()->role === 'admin')
                        <form
                            action="{{ route('feedRecords.destroy', $record->id) }}"
                            method="POST"
                            style="display:inline-block;"
                            class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-delete" onclick="confirmDeletion(this.form)">
                                Eliminar
                            </button>
                        </form>
                        @endif
                        @endauth

                    </td>
                </tr>

                <!-- Formulario edición -->
                <tr class="edit-form" id="edit-form-{{ $record->id }}">
                    <form
                        action="{{ route('feedRecords.update', $record->id) }}"
                        method="POST"
                        onsubmit="return confirm('¿Seguro que quieres guardar los cambios?');">
                        @csrf
                        @method('PUT')
                        <td>
                            <input type="date" name="feeding_date" value="{{ $record->feeding_date ? $record->feeding_date->format('Y-m-d') : '' }}" class="edit-input edit-input-date" required>
                        </td>
                        <td><input type="number" name="r1" value="{{ $record->r1 }}" min="0" step="0.01" class="edit-input" required></td>
                        <td><input type="number" name="r2" value="{{ $record->r2 }}" min="0" step="0.01" class="edit-input" required></td>
                        <td><input type="number" name="r3" value="{{ $record->r3 }}" min="0" step="0.01" class="edit-input" required></td>
                        <td><input type="number" name="r4" value="{{ $record->r4 }}" min="0" step="0.01" class="edit-input" required></td>
                        <td><input type="number" name="r5" value="{{ $record->r5 }}" min="0" step="0.01" class="edit-input" required></td>
                        <td>
                            <input type="number" disabled value="{{ $record->daily_ration }}" class="edit-input" title="Ración total calculada">
                        </td>
                        <td>
                            <input type="number" name="crude_protein" value="{{ $record->crude_protein }}" min="0" step="0.01" class="edit-input" required>
                        </td>
                        <td>
                            <textarea name="justification" class="edit-textarea" rows="2">{{ $record->justification }}</textarea>
                        </td>
                        <td colspan="2">
                            <button type="submit" class="btn btn-edit" style="margin-top:5px;">Guardar</button>
                            <button type="button" class="btn btn-delete" onclick="cancelEdit({{ $record->id }})" style="margin-top:5px;">Cancelar</button>
                        </td>
                    </form>
                </tr>
                @endforeach
            </tbody>
        </table>
    </details>
    @endforeach
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showEditForm(id) {
        document.getElementById('edit-form-' + id).style.display = 'table-row';
        let row = document.getElementById('record-row-' + id);
        if (row) row.style.display = 'none';
    }

    function cancelEdit(id) {
        document.getElementById('edit-form-' + id).style.display = 'none';
        let row = document.getElementById('record-row-' + id);
        if (row) row.style.display = 'table-row';
    }

    async function confirmDeletion(form) {
        const firstConfirm = await Swal.fire({
            title: '¿Seguro que quieres eliminar este registro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            backdrop: 'rgba(0,0,0,0.4)',
            customClass: {
                confirmButton: 'btn btn-danger px-4',
                cancelButton: 'btn btn-secondary px-4'
            },
            buttonsStyling: false
        });

        if (firstConfirm.isConfirmed) {
            const secondConfirm = await Swal.fire({
                title: 'Esta acción es irreversible',
                text: '¿Estás completamente seguro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar definitivamente',
                cancelButtonText: 'Cancelar',
                backdrop: 'rgba(0,0,0,0.4)',
                customClass: {
                    confirmButton: 'btn btn-danger px-4',
                    cancelButton: 'btn btn-secondary px-4'
                },
                buttonsStyling: false
            });

            if (secondConfirm.isConfirmed) {
                form.submit();
            }
        }
    }
</script>

@if (session('success'))
<script>
    Swal.fire({
        title: '¡Éxito!',
        text: '{{ session("success") }}',
        icon: 'success',
        confirmButtonText: 'Aceptar',
        backdrop: 'rgba(0,0,0,0.4)',
        customClass: {
            confirmButton: 'btn btn-primary px-4'
        }
    });
</script>
@endif
@endsection