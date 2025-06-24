<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sowing;

class GraficController extends Controller
{
    // Mostrar el dashboard con el select de comparación
    public function index()
    {
        // Cargar relaciones con la más reciente incluida
        $sowings = Sowing::with([
            'species',
            'type',
            'pond',
            'identifier',
            'lastMonitoring'
        ])->orderByDesc('id')->get();

        return view('auth.admin.grafic.grafic', [
            'sowings' => $sowings,
            'comparisonResult' => null,
            'selectedSowings' => [],
        ]);
    }

    // Procesar la comparación entre siembras
    public function compare(Request $request)
    {
        $ids = $request->input('sowing_ids');

        // Validar que haya al menos una siembra seleccionada
        if (!$ids || count($ids) < 1) {
            return redirect()->back()->withErrors('Debes seleccionar al menos una siembra.');
        }

        // Validar que no se comparen dos veces la misma siembra
        if (count($ids) > 1 && count(array_unique($ids)) < count($ids)) {
            return redirect()->back()->withErrors('No puedes seleccionar la misma siembra dos veces.');
        }

        // Cargar las siembras seleccionadas con todas las relaciones necesarias
        $sowings = Sowing::with([
            'species',
            'type',
            'pond',
            'identifier',
            'mortalities',
            'dietMonitorings.feedRecords',
            'lastMonitoring'
        ])->whereIn('id', $ids)->get();

        return view('auth.admin.grafic.grafic', [
            'sowings' => Sowing::with([
                'species',
                'type',
                'pond',
                'identifier',
                'lastMonitoring'
            ])->orderByDesc('id')->get(),
            'selectedSowings' => $sowings,
            'comparisonResult' => null,
        ]);
    }
}
