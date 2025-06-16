<?php

namespace App\Http\Controllers;

use App\Models\DietMonitoring;
use App\Models\Mortality;
use App\Models\Sowing;
use App\Models\pond_unit_code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MortalityController extends Controller
{
    public function create()
    {
        $pondCodeId = request()->pond_code_id;

        if ($pondCodeId) {
            $sowing = Sowing::with(['dietMonitorings' => function ($query) {
                $query->orderBy('sampling_date');
            }])
                ->where('identifier_id', $pondCodeId)
                ->where('state', 'inicializada')
                ->first();

            if (!$sowing) {
                return redirect()->back()->with('error', 'No hay una siembra inicializada para este estanque.');
            }

            $monitorings = $sowing->dietMonitorings;
            $seguimientosTotal = $monitorings->count();

            if ($seguimientosTotal == 0) {
                return redirect()->back()->with('error', 'Debe registrar al menos un seguimiento antes de registrar mortalidad.');
            }

            // Obtener la fecha del primer seguimiento
            $firstMonitoringDate = $monitorings->first()->sampling_date;

            // Contar registros de mortalidad desde la fecha del primer seguimiento, filtrando por sowing_id
            $mortalityCountFromFirst = Mortality::where('pond_code_id', $pondCodeId)
                ->where('sowing_id', $sowing->id)
                ->where('created_at', '>=', $firstMonitoringDate)
                ->count();

            // Límite permitido según cantidad de seguimientos
            $limitePermitido = $seguimientosTotal * 15;

            if ($mortalityCountFromFirst >= $limitePermitido) {
                return redirect()->back()->with('error', 'Debe realizar el seguimiento número ' . ($seguimientosTotal + 1) . ' para habilitar nuevos registros de mortalidad.');
            }
        }

        $lakes = \App\Models\GeoPond::all();
        return view('auth.admin.Unit_registration.Mortality.form', compact('lakes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'datetime' => 'required|date',
            'amount' => 'required|integer|min:0',
            'observation' => 'nullable|string',
            'pond_code_id' => 'required|exists:pond_unit_codes,id',
        ]);

        $pondCodeId = $request->pond_code_id;

        $sowing = Sowing::where('identifier_id', $pondCodeId)
            ->where('state', 'inicializada')
            ->first();

        if (!$sowing) {
            return redirect()->back()->with('error', 'No hay siembra inicializada para este estanque.');
        }

        // Validaciones similares a create(), filtrando por sowing_id
        $monitorings = DietMonitoring::where('sowing_id', $sowing->id)
            ->orderBy('sampling_date')
            ->get();

        $seguimientosTotal = $monitorings->count();

        if ($seguimientosTotal == 0) {
            return redirect()->back()->with('error', 'Debe registrar al menos un seguimiento antes de registrar mortalidad.');
        }

        $firstMonitoringDate = $monitorings->first()->sampling_date;

        $mortalityCountFromFirst = Mortality::where('pond_code_id', $pondCodeId)
            ->where('sowing_id', $sowing->id)
            ->where('created_at', '>=', $firstMonitoringDate)
            ->count();

        $limitePermitido = $seguimientosTotal * 15;

        if ($mortalityCountFromFirst >= $limitePermitido) {
            return redirect()->back()->with('error', 'Debe realizar el seguimiento número ' . ($seguimientosTotal + 1) . ' para habilitar nuevos registros de mortalidad.');
        }

        // Obtener el seguimiento más reciente
        $lastMonitoring = DietMonitoring::where('sowing_id', $sowing->id)
            ->latest('sampling_date')
            ->first();

        if (!$lastMonitoring || Carbon::now()->diffInDays($lastMonitoring->sampling_date) > 15) {
            return redirect()->back()->with('error', 'Debe realizar el seguimiento quincenal para continuar registrando mortalidad.');
        }

        // Contar registros de mortalidad desde la fecha del último seguimiento, filtrando por sowing_id
        $mortalityCount = Mortality::where('pond_code_id', $pondCodeId)
            ->where('sowing_id', $sowing->id)
            ->where('created_at', '>=', $lastMonitoring->sampling_date)
            ->count();

        if ($mortalityCount >= 15) {
            return redirect()->back()->with('error', 'Límite de 15 registros alcanzado desde el último seguimiento.');
        }

        // Calcular balance de peces después de esta mortalidad
        $totalMortalitySum = Mortality::where('pond_code_id', $pondCodeId)
            ->where('sowing_id', $sowing->id)
            ->sum('amount');

        $fish_balance = $sowing->fish_count - $totalMortalitySum - $request->amount;

        Mortality::create([
            'datetime' => $request->datetime,
            'amount' => $request->amount,
            'fish_balance' => $fish_balance,
            'observation' => $request->observation,
            'pond_code_id' => $pondCodeId,
            'user_id' => Auth::id(),
            'sowing_id' => $sowing->id,
        ]);

        return redirect()->route('mortality.create', ['pond_code_id' => $pondCodeId])
            ->with('success', 'Registro de mortalidad guardado exitosamente.');
    }

    public function getPondsByPondId(Request $request)
    {
        $ponds = pond_unit_code::where('pond_id', $request->pond_id)->get();
        return response()->json($ponds);
    }

    public function index()
    {
        $sowing = \App\Models\Sowing::where('state', 'inicializada')->first();
        $expected = $sowing ? $sowing->fish_count : 0;
        $actual = 0;

        if ($sowing) {
           $pondCodeIds = pond_unit_code::where('id', $sowing->identifier_id)->pluck('id');


            $filtro = Mortality::with(['user', 'pondUnitCode.pond'])
                ->where('sowing_id', $sowing->id)
                ->whereIn('pond_code_id', $pondCodeIds)
                ->get();

            $actual = Mortality::whereIn('pond_code_id', $pondCodeIds)
                ->where('sowing_id', $sowing->id)
                ->sum('amount');
        } else {
            $filtro = collect();
        }

        return view('auth.admin.Unit_registration.Mortality.Filtro', compact('filtro', 'expected', 'actual'));
    }

    public function getSowingData(Request $request)
    {
        $pondCodeId = $request->input('pond_code_id');

        $sowing = Sowing::where('identifier_id', $pondCodeId)
            ->where('state', 'inicializada')
            ->first();

        if (!$sowing) {
            return response()->json(['error' => 'No hay siembra inicializada para este estanque.'], 404);
        }

        // Obtener la mortalidad total hasta el momento filtrando por sowing_id
        $totalMortality = Mortality::where('pond_code_id', $pondCodeId)
            ->where('sowing_id', $sowing->id)
            ->sum('amount');

        $fishBalance = $sowing->fish_count - $totalMortality;

        return response()->json([
            'fish_count' => $sowing->fish_count,
            'total_mortality' => $totalMortality,
            'fish_balance' => $fishBalance
        ]);
    }
}
