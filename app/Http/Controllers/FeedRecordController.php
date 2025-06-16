<?php

namespace App\Http\Controllers;

use App\Models\FeedRecord;
use App\Models\DietMonitoring;
use App\Models\Sowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedRecordController extends Controller
{
    /**
     * Muestra el formulario de registro de alimentación.
     */
    public function create($sowingId)
    {
        $sowing = Sowing::findOrFail($sowingId);

        $dietMonitoring = DietMonitoring::where('sowing_id', $sowingId)
            ->orderBy('sampling_date', 'desc')
            ->first();

        return view('auth.admin.feed_records.create', compact('sowing', 'dietMonitoring'));
    }

    /**
     * Muestra el historial de alimentación.
     */
    public function showFeedHistory($sowingId)
    {
        $sowing = Sowing::findOrFail($sowingId);

        // Obtener registros de alimentación relacionados a diet_monitoring de esta siembra
        $feedRecords = FeedRecord::whereHas('dietMonitoring', function ($query) use ($sowingId) {
            $query->where('sowing_id', $sowingId);
        })->orderBy('created_at', 'desc')->get();

        return view('auth.admin.feed_records.histial_alimentacion', compact('feedRecords', 'sowing'));
    }

    /**
     * Guarda el registro de alimentación.
     */
 public function store(Request $request)
{
    foreach (['r1', 'r2', 'r3', 'r4', 'r5', 'crude_protein'] as $field) {
        if ($request->has($field)) {
            $value = str_replace('.', '', $request->input($field)); // elimina separador de miles
            $value = str_replace(',', '.', $value); // convierte coma decimal a punto decimal
            $request->merge([$field => $value]);
        }
    }

    $request->validate([
        'feeding_date' => 'required|date',
        'diet_monitoring_id' => 'required|exists:diet_monitorings,id',
        'r1' => 'nullable|numeric|min:0',
        'r2' => 'nullable|numeric|min:0',
        'r3' => 'nullable|numeric|min:0',
        'r4' => 'nullable|numeric|min:0',
        'r5' => 'nullable|numeric|min:0',
        'crude_protein' => 'required|numeric|min:0',
    ]);

    $dietMonitoring = DietMonitoring::find($request->diet_monitoring_id);
    if (!$dietMonitoring) {
        return back()->withErrors(['diet_monitoring_id' => 'Seguimiento de dieta no válido'])->withInput();
    }

    $sowingId = $dietMonitoring->sowing_id;
    $totalDietMonitorings = DietMonitoring::where('sowing_id', $sowingId)->count();
    $lastDietMonitoring = DietMonitoring::where('sowing_id', $sowingId)
        ->orderBy('sampling_date', 'desc')
        ->first();
    $feedRecordsCountForCurrentMonitoring = FeedRecord::where('diet_monitoring_id', $dietMonitoring->id)->count();

    if ($dietMonitoring->id != $lastDietMonitoring->id) {
        return back()->withErrors(['diet_monitoring_id' => 'Solo puede registrar alimentación para el seguimiento más reciente. Por favor, realice un nuevo seguimiento antes de continuar.'])->withInput();
    }

    if ($feedRecordsCountForCurrentMonitoring >= 15) {
        if ($totalDietMonitorings <= 1) {
            return back()->withErrors(['diet_monitoring_id' => 'Para poder hacer más registros de alimentación debe realizar un nuevo seguimiento.'])->withInput();
        } else {
            return back()->withErrors(['diet_monitoring_id' => 'Ha alcanzado el límite de registros para este seguimiento. Por favor, use el siguiente seguimiento para continuar.'])->withInput();
        }
    }

    $totalRations = '0.00';
    foreach (['r1', 'r2', 'r3', 'r4', 'r5'] as $field) {
        $val = $request->input($field, '0');
        if (!is_numeric($val)) {
            $val = '0';
        }
        $totalRations = bcadd($totalRations, number_format((float)$val, 2, '.', ''), 2);
    }

    $expectedRation = number_format($dietMonitoring->daily_feed, 2, '.', '');

    if (bccomp($totalRations, $expectedRation, 2) !== 0) {
        if (empty($request->justification)) {
            return back()->withErrors([
                'justification' => "La suma de las raciones ({$totalRations} g) no coincide con la ración completa recomendada ({$expectedRation} g). Por favor justifique la diferencia."
            ])->withInput();
        }
        $finalJustification = "Ración calculada: {$totalRations} g, Ración recomendada: {$expectedRation} g. Justificación: " . $request->justification;
    } else {
        $finalJustification = !empty($request->justification) ? $request->justification : "alimentación completa";
    }

    // ✅ Intentar guardar con manejo del error
    try {
        $feedRecord = FeedRecord::create([
            'feeding_date' => $request->feeding_date,
            'r1' => $request->r1,
            'r2' => $request->r2,
            'r3' => $request->r3,
            'r4' => $request->r4,
            'r5' => $request->r5,
            'daily_ration' => $totalRations,
            'crude_protein' => $request->crude_protein,
            'justification' => $finalJustification,
            'diet_monitoring_id' => $dietMonitoring->id,
            'responsible_id' => Auth::id(),
        ]);

        return redirect()->route('feed_records.create', ['sowingId' => $sowingId])
            ->with('success', 'Registro de alimentación creado exitosamente.');
    } catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() === '22003') {
            return back()->withErrors([
                'crude_protein' => 'El valor ingresado en proteína cruda es demasiado grande. Por favor, revise el dato.'
            ])->withInput();
        }

        return back()->withErrors([
            'error' => 'Ocurrió un error al guardar el registro. Intenta nuevamente.'
        ])->withInput();
    }
}

    public function update(Request $request, $id)
    {
        $record = FeedRecord::findOrFail($id);
        foreach (['r1', 'r2', 'r3', 'r4', 'r5', 'crude_protein'] as $field) {
            if ($request->has($field)) {
                $request->merge([
                    $field => str_replace(',', '.', str_replace('.', '', $request->$field))
                ]);
            }
        }

        $validated = $request->validate([
            'feeding_date' => 'required|date',
            'r1' => 'required|numeric|min:0',
            'r2' => 'required|numeric|min:0',
            'r3' => 'required|numeric|min:0',
            'r4' => 'required|numeric|min:0',
            'r5' => 'required|numeric|min:0',
            'crude_protein' => 'required|numeric|min:0',
            'justification' => 'nullable|string|max:1000',
        ]);

        // Asignación de valores
        $record->feeding_date = $validated['feeding_date'];
        $record->r1 = $validated['r1'];
        $record->r2 = $validated['r2'];
        $record->r3 = $validated['r3'];
        $record->r4 = $validated['r4'];
        $record->r5 = $validated['r5'];
        $record->crude_protein = $validated['crude_protein'];

        // Calcular ración total
        $record->daily_ration = $record->r1 + $record->r2 + $record->r3 + $record->r4 + $record->r5;

        // Comparar con la ración esperada del monitoreo
        $expectedRation = number_format($record->dietMonitoring->daily_feed, 2, '.', '');
        $totalRations = number_format($record->daily_ration, 2, '.', '');

        if (bccomp($totalRations, $expectedRation, 2) !== 0) {
            $userJustification = $request->justification ?? '';
            $record->justification = "Ración calculada: {$totalRations} g, Ración recomendada: {$expectedRation} g. Justificación: {$userJustification}";
        } else {
            $record->justification = !empty($request->justification) ? $request->justification : "alimentación completa";
        }

        $record->save();

        return redirect()->back()->with('success', 'Registro actualizado correctamente');
    }

    public function edit($id)
    {
        $record = FeedRecord::findOrFail($id);
        return view('auth.admin.feed_records.edit', compact('record'));
    }

    public function destroy($id)
    {
        // Buscar el registro por id
        $feedRecord = FeedRecord::findOrFail($id);

        // Eliminar el registro
        $feedRecord->delete();

        // Redireccionar con mensaje de éxito
        return redirect()->back()->with('success', 'Registro eliminado correctamente.');
    }
}
