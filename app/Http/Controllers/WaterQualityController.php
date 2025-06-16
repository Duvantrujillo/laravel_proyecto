<?php 

namespace App\Http\Controllers;

use App\Models\Sowing;
use App\Models\WaterQuality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaterQualityController extends Controller
{
    // Mostrar formulario de registro de calidad de agua
    public function create(Sowing $sowing)
    {
        if ($sowing->state !== 'inicializada') {
            return redirect()->back()->with('error', 'No se puede registrar calidad de agua porque la siembra está finalizada.');
        }

        return view('auth.admin.water_quality.create', compact('sowing'));
    }

    // Guardar registro de calidad de agua
  public function store(Request $request, Sowing $sowing)
{
    if ($sowing->state !== 'inicializada') {
        return redirect()->back()->with('error', 'No se puede registrar calidad de agua porque la siembra está finalizada.');
    }

    // Campos que se consideran obligatorios (ajústalos si alguno no es obligatorio)
    $fields = [
        'ph',
        'temperature',
        'ammonia',
        'turbidity',
        'dissolved_oxygen',
        'nitrites',
        'nitrates',
    ];

    $hasEmptyField = false;
    foreach ($fields as $field) {
        if ($request->input($field) === null || $request->input($field) === '') {
            $hasEmptyField = true;
            break;
        }
    }

    // Si hay campos vacíos y no hay justificación, devuelvo error
    if ($hasEmptyField && (!$request->has('justification') || trim($request->input('justification')) === '')) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Debe justificar por qué hay campos vacíos antes de guardar el registro.');
    }

    // Si no hay campos vacíos y no se ha puesto justificación, la completamos automáticamente
    $justification = $request->input('justification');
    if (!$hasEmptyField && (trim($justification) === '' || $justification === null)) {
        $justification = 'Calidad del agua completa';
    }

    // Validaciones básicas (puedes ajustar según tu necesidad)
 $request->validate([
    'date' => 'required|date',
    'time' => 'required',
    'ph' => 'nullable|numeric|between:0,14',
    'temperature' => 'nullable|numeric|between:-10,50',
    'ammonia' => 'nullable|numeric|between:0,100',
    'turbidity' => 'nullable|numeric|between:0,1000',
    'dissolved_oxygen' => 'nullable|numeric|between:0,50',
    'nitrites' => 'nullable|numeric|between:0,999.99',
    'nitrates' => 'nullable|numeric|between:0,999.99',
    'justification' => 'nullable|string',
], [
    'ph.numeric' => 'El pH debe ser un número.',
    'ph.between' => 'El pH debe estar entre 0 y 14.',

    'temperature.numeric' => 'La temperatura debe ser un número.',
    'temperature.between' => 'La temperatura debe estar entre -10 y 50 °C.',

    'ammonia.numeric' => 'El valor de amoníaco debe ser un número.',
    'ammonia.between' => 'El valor de amoníaco debe estar entre 0 y 100.',

    'turbidity.numeric' => 'La turbidez debe ser un número.',
    'turbidity.between' => 'La turbidez debe estar entre 0 y 1000.',

    'dissolved_oxygen.numeric' => 'El oxígeno disuelto debe ser un número.',
    'dissolved_oxygen.between' => 'El oxígeno disuelto debe estar entre 0 y 50.',

    'nitrites.numeric' => 'El valor de nitritos debe ser un número.',
    'nitrites.between' => 'El valor de nitritos debe estar entre 0 y 999.99.',

    'nitrates.numeric' => 'El valor de nitratos debe ser un número.',
    'nitrates.between' => 'El valor de nitratos es demasiado grande (máximo 999.99).',
]);


    // Guardar el registro
    WaterQuality::create([
        'sowing_id' => $sowing->id,
        'date' => $request->date,
        'time' => $request->time,
        'ph' => $request->ph,
        'temperature' => $request->temperature,
        'ammonia' => $request->ammonia,
        'turbidity' => $request->turbidity,
        'dissolved_oxygen' => $request->dissolved_oxygen,
        'nitrites' => $request->nitrites,
        'nitrates' => $request->nitrates,
        'user_id' => Auth::id(),
        'justification' => $justification,
    ]);

    return redirect()->route('water_quality.create', $sowing->id)->with('success', 'Registro de calidad de agua guardado exitosamente.');
}

    // Mostrar historial de registros de calidad de agua para una siembra
    public function history($sowingId)
    {
        // Aquí agregamos el eager loading con 'user'
        $waterQualities = WaterQuality::where('sowing_id', $sowingId)
                            ->with('user')
                            ->orderBy('date', 'desc')
                            ->get();

        return view('auth.admin.water_quality.history', compact('waterQualities', 'sowingId'));
    }







// Editar
public function edit($id)
{
    $quality = WaterQuality::findOrFail($id);
    return view('auth.admin.water_quality.edit', compact('quality'));
}

// Actualizar
public function update(Request $request, $id)
{
    $quality = WaterQuality::findOrFail($id);

    // Campos que se consideran obligatorios (ajústalos si alguno no es obligatorio)
    $fields = [
        'ph',
        'temperature',
        'ammonia',
        'turbidity',
        'dissolved_oxygen',
        'nitrites',
        'nitrates',
    ];

    $hasEmptyField = false;
    foreach ($fields as $field) {
        if ($request->input($field) === null || $request->input($field) === '') {
            $hasEmptyField = true;
            break;
        }
    }

    // Si hay campos vacíos y no hay justificación, devuelvo error
    if ($hasEmptyField && (!$request->has('justification') || trim($request->input('justification')) === '')) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Debe justificar por qué hay campos vacíos antes de guardar el registro.');
    }

    // Si no hay campos vacíos y no se ha puesto justificación, la completamos automáticamente
    $justification = $request->input('justification');
    if (!$hasEmptyField && (trim($justification) === '' || $justification === null)) {
        $justification = 'Calidad del agua completa';
    }

    // Validaciones
    $request->validate([
        'date' => 'required|date',
        'time' => 'required',
        'ph' => 'nullable|numeric|between:0,14',
        'temperature' => 'nullable|numeric|between:-10,50',
        'ammonia' => 'nullable|numeric|between:0,100',
        'turbidity' => 'nullable|numeric|between:0,1000',
        'dissolved_oxygen' => 'nullable|numeric|between:0,50',
        'nitrites' => 'nullable|numeric|between:0,999.99',
        'nitrates' => 'nullable|numeric|between:0,999.99',
        'justification' => 'nullable|string',
    ], [
        'ph.numeric' => 'El pH debe ser un número.',
        'ph.between' => 'El pH debe estar entre 0 y 14.',

        'temperature.numeric' => 'La temperatura debe ser un número.',
        'temperature.between' => 'La temperatura debe estar entre -10 y 50 °C.',

        'ammonia.numeric' => 'El valor de amoníaco debe ser un número.',
        'ammonia.between' => 'El valor de amoníaco debe estar entre 0 y 100.',

        'turbidity.numeric' => 'La turbidez debe ser un número.',
        'turbidity.between' => 'La turbidez debe estar entre 0 y 1000.',

        'dissolved_oxygen.numeric' => 'El oxígeno disuelto debe ser un número.',
        'dissolved_oxygen.between' => 'El oxígeno disuelto debe estar entre 0 y 50.',

        'nitrites.numeric' => 'El valor de nitritos debe ser un número.',
        'nitrites.between' => 'El valor de nitritos debe estar entre 0 y 999.99.',

        'nitrates.numeric' => 'El valor de nitratos debe ser un número.',
        'nitrates.between' => 'El valor de nitratos es demasiado grande (máximo 999.99).',
    ]);

    // Actualizar
    $quality->update([
        'date' => $request->date,
        'time' => $request->time,
        'ph' => $request->ph,
        'temperature' => $request->temperature,
        'ammonia' => $request->ammonia,
        'turbidity' => $request->turbidity,
        'dissolved_oxygen' => $request->dissolved_oxygen,
        'nitrites' => $request->nitrites,
        'nitrates' => $request->nitrates,
        'justification' => $justification,
    ]);

    return redirect()->back()->with('success', 'Registro actualizado con éxito.');
}


// Eliminar
public function destroy($id)
{
    $quality = WaterQuality::findOrFail($id);
    $quality->delete();

    return redirect()->back()->with('success', 'Registro eliminado con éxito.');
}















}
