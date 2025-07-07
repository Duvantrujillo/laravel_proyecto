<?php

namespace App\Http\Controllers;

use App\Models\Sowing;
use Illuminate\Http\Request;
use App\Models\DietMonitoring;
use App\Models\GeoPond;
use Carbon\Carbon;
use App\Models\Mortality;

use App\Models\pond_unit_code;


class DietMonitoringController extends Controller
{
    // Función para limpiar y convertir números formateados es-CO a float
    private function parseNumber($number)
    {
        if (!$number) return 0;
        // Quitar puntos de miles y cambiar coma decimal por punto
        $number = str_replace('.', '', $number);
        $number = str_replace(',', '.', $number);
        return floatval($number);
    }

  public function create()
{
    if (auth()->user()->role === 'admin') {
        // Admin ve todas las siembras inicializadas
        $sowings = Sowing::where('state', 'inicializada')->get();
    } else {
        // Pasante solo ve las asignadas e inicializadas
        $sowings = auth()->user()->assignedSowings()->where('state', 'inicializada')->get();
    }

    return view('auth.admin.diet.diet_monitoring.Form', compact('sowings'));
}


    public function index(Request $request, $sowing_id)
    {
        $sowing = Sowing::with('dietMonitorings')->findOrFail($sowing_id);

        $isFirst = $sowing->dietMonitorings->isEmpty();
        $lastMonitoring = $sowing->dietMonitorings->last();

        // Mortalidad acumulada solo de la siembra activa (filtrado por sowing_id)
        $cumulativeMortality = Mortality::where('pond_code_id', $sowing->identifier_id)
            ->where('sowing_id', $sowing->id)
            ->sum('amount');

        // Saldo de peces = sembrados - mortalidad acumulada
        $fishBalance = $sowing->fish_count - $cumulativeMortality;

        return view('auth.admin.diet.diet_monitoring.diet_monitoring', [
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

        // Convertir números formateados a float antes de operar y guardar
        $average_weight = $this->parseNumber($request->average_weight);
        $fish_balance = $this->parseNumber($request->fish_balance);
        $biomass_percentage = $this->parseNumber($request->biomass_percentage);
        $biomass = $this->parseNumber($request->biomass);
        $daily_feed = $this->parseNumber($request->daily_feed);
        $ration_number = $this->parseNumber($request->ration_number);
        $ration = $this->parseNumber($request->ration);

        $weight_gain = $isFirst 
            ? 0 
            : $average_weight - $lastMonitoring->average_weight;

        if (!$isFirst) {
            $mortalityCount = Mortality::where('pond_code_id', $sowing->identifier_id)
                ->where('sowing_id', $sowing->id)
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
                ->where('sowing_id', $sowing->id)
                ->where('datetime', '<=', $request->sampling_date)
                ->sum('amount');

        $request->validate([
            'feed_type' => 'required|string',
        ]);

        DietMonitoring::create([
            'sampling_date' => $request->sampling_date,
            'average_weight' => $average_weight,
            'fish_balance' => $fish_balance,
            'biomass_percentage' => $biomass_percentage,
            'biomass' => $biomass,
            'daily_feed' => $daily_feed,
            'ration_number' => $ration_number,
            'ration' => $ration,
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

        return view('auth.admin.diet.diet_monitoring.diet monitoring filter', compact('sowing', 'monitorings'));
    }

    public function finish($id)
    {
        $sowing = Sowing::findOrFail($id);

        if ($sowing->state === 'inicializada') {
            $sowing->state = 'terminada';
            $sowing->sowing_completion = Carbon::now()->setTimezone('America/Bogota');
            $sowing->save();
        }

        return redirect()->back()->with('success', 'El seguimiento ha sido terminado.');
    }

 public function terminated(Request $request)
{
    $query = Sowing::with(['dietMonitorings', 'type.species', 'pond', 'identifier'])
        ->where('state', 'terminada');

    // Filtros
    if ($request->filled('pond_id')) {
        $query->where('pond_id', $request->pond_id);
    }

    if ($request->filled('identifier_id')) {
        $query->where('identifier_id', $request->identifier_id);
    }

    if ($request->filled('date')) {
        $query->whereDate('sowing_date', $request->date);
    }

    $sowings = $query->orderBy('sowing_completion', 'desc')->get();

    // Para cargar opciones en el filtro
    $ponds = GeoPond::all();
    $identifiers = pond_unit_code::all();

    return view('auth.admin.diet.diet_monitoring.terminated_monitorings', compact('sowings', 'ponds', 'identifiers'));
}
}
