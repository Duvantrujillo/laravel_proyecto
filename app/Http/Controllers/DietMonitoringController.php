<?php

namespace App\Http\Controllers;

use App\Models\Sowing;
use Illuminate\Http\Request;
use App\Models\DietMonitoring;
use Carbon\Carbon;
use App\Models\Mortality;

class DietMonitoringController extends Controller
{
    public function create()
    {
        $sowings = Sowing::where('state', 'inicializada')->get();
        return view('auth.user.diet.diet_monitoring.Form', compact('sowings'));
    }

 public function index(Request $request, $sowing_id)
{
    $sowing = Sowing::with('dietMonitorings')->findOrFail($sowing_id);

    $isFirst = $sowing->dietMonitorings->isEmpty();
    $lastMonitoring = $sowing->dietMonitorings->last();

    // Mortalidad acumulada solo de la siembra activa (filtrado por sowing_id)
    $cumulativeMortality = Mortality::where('pond_code_id', $sowing->identifier_id)
        ->where('sowing_id', $sowing->id) // FILTRO agregado
        ->sum('amount');

    // Saldo de peces = sembrados - mortalidad acumulada
    $fishBalance = $sowing->fish_count - $cumulativeMortality;

    return view('auth.user.diet.diet_monitoring.diet_monitoring', [
        'sowing' => $sowing,
        'isFirst' => $isFirst,
        'lastMonitoring' => $lastMonitoring,
        'cumulativeMortality' => $cumulativeMortality,
        'fishBalance' => $fishBalance,
    ]);
}

public function store(Request $request)
{
    $sowing = Sowing::findOrFail($request->sowing_id);
    $lastMonitoring = $sowing->dietMonitorings->last();

    $isFirst = $sowing->dietMonitorings->isEmpty();

    $weight_gain = $isFirst 
        ? 0 
        : $request->average_weight - $lastMonitoring->average_weight;

    if (!$isFirst) {
        $mortalityCount = Mortality::where('pond_code_id', $sowing->identifier_id)
            ->where('sowing_id', $sowing->id)  // FILTRO agregado
            ->where('datetime', '<=', $request->sampling_date)
            ->count();

        $blocks = floor($mortalityCount / 15);

        if ($blocks == 0) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Debe haber al menos 15 registros de mortalidad para continuar con el seguimiento.');
        }
    }

    $cumulative_mortality = $isFirst
        ? 0
        : Mortality::where('pond_code_id', $sowing->identifier_id)
            ->where('sowing_id', $sowing->id)  // FILTRO agregado
            ->where('datetime', '<=', $request->sampling_date)
            ->sum('amount');

    $request->validate([
        'feed_type' => 'required|string',
    ]);

    DietMonitoring::create([
        'sampling_date' => $request->sampling_date,
        'average_weight' => $request->average_weight,
        'fish_balance' => $request->fish_balance,
        'biomass_percentage' => $request->biomass_percentage,
        'biomass' => $request->biomass,
        'daily_feed' => $request->daily_feed,
        'ration_number' => $request->ration_number,
        'ration' => $request->ration,
        'weight_gain' => $weight_gain,
        'cumulative_mortality' => $cumulative_mortality,
        'feed_type' => $request->feed_type,
        'sowing_id' => $request->sowing_id,
    ]);

    return redirect()
        ->route('diet_monitoring.index', ['sowing_id' => $sowing->id])
        ->with('success', 'Seguimiento registrado correctamente.');
}




    public function showBySowing($sowingId)
    {
        $sowing = Sowing::where('id', $sowingId)
            ->where('state', 'inicializada')
            ->firstOrFail();

        $monitorings = $sowing->dietMonitorings;

        return view('auth.user.diet.diet_monitoring.diet monitoring filter', compact('sowing', 'monitorings'));
    }


public function finish($id)
{
    $sowing = Sowing::findOrFail($id);

    if ($sowing->state === 'inicializada') {
        $sowing->state = 'terminada';
        $sowing->sowing_completion = Carbon::now()->setTimezone('America/Bogota'); // Ajusta tu zona horaria si es necesario
        $sowing->save();
    }

    return redirect()->back()->with('success', 'El seguimiento ha sido terminado.');
}



public function terminated()
{
    // Obtener todas las siembras con estado 'terminada' y sus seguimientos
    $sowings = Sowing::where('state', 'terminada')
        ->with(['dietMonitorings', 'type.species', 'pond', 'identifier'])
        ->get();

    return view('auth.user.diet.diet_monitoring.terminated_monitorings', compact('sowings'));
}

}
