<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sowing;

class GraficController extends Controller
{
    // Mostrar el dashboard con el select de comparación
    public function index()
    {
        // Cargar relaciones para mostrar nombres reales en lugar de IDs
        $sowings = Sowing::with(['species', 'type', 'pond', 'identifier'])->get();

        return view('auth.admin.grafic.grafic', [
            'sowings' => $sowings,
            'comparisonResult' => null,
            'selectedSowings' => [],
        ]);
    }

    // Procesar la comparación entre dos siembras
    public function compare(Request $request)
    {
        $ids = $request->input('sowing_ids');

        if (!$ids || count($ids) != 2) {
            return redirect()->back()->withErrors('Debes seleccionar exactamente dos registros de siembra.');
        }

        // Cargar las siembras seleccionadas con sus relaciones
        $sowings = Sowing::with(['species', 'type', 'pond', 'identifier'])
            ->whereIn('id', $ids)
            ->get();

        // Comparación textual (esto se puede mejorar según tus necesidades)
        $comparisonResult = "Comparación:\n\n" .
            ($sowings[0]->identifier->code ?? 'Sin código') . " (ID: {$sowings[0]->id}) vs " .
            ($sowings[1]->identifier->code ?? 'Sin código') . " (ID: {$sowings[1]->id})";

        // Devolver la vista con todos los datos necesarios
        return view('auth.admin.grafic.grafic', [
            'sowings' => Sowing::with(['species', 'type', 'pond', 'identifier'])->get(), // para el select
            'selectedSowings' => $sowings,      // para las tarjetas comparativas
            'comparisonResult' => $comparisonResult,
        ]);
    }
}
