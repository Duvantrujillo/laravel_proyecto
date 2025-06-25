<?php

namespace App\Http\Controllers;

use App\Models\DietMonitoring;
use App\Models\Mortality;
use App\Models\Sowing;
use App\Models\pond_unit_code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
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
            $pondCodeIds = \App\Models\pond_unit_code::where('id', $sowing->identifier_id)->pluck('id');

            $filtro = \App\Models\Mortality::with(['user', 'pondUnitCode.pond'])
                ->where('sowing_id', $sowing->id)
                ->whereIn('pond_code_id', $pondCodeIds)
                ->get();

            $actual = \App\Models\Mortality::whereIn('pond_code_id', $pondCodeIds)
                ->where('sowing_id', $sowing->id)
                ->sum('amount');
        } else {
            $filtro = collect();
        }

        $now = \Carbon\Carbon::now('UTC'); // Tiempo real del servidor
        return view('auth.admin.Unit_registration.Mortality.Filtro', compact('filtro', 'expected', 'actual', 'now'));
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
    public function history($sowingId)
    {
        $filtro = \App\Models\Mortality::where('sowing_id', $sowingId)
            ->orderBy('datetime', 'desc')
            ->get();

        return view('auth.admin.Unit_registration.Mortality.Filtro', compact('filtro'));
    }



    public function pdfEstanque($pond_code_id, Request $request)
    {
        // Buscar el estanque
        $pond = pond_unit_code::findOrFail($pond_code_id);

        // Obtener la siembra actual (enviada desde la vista)
        $sowingId = $request->get('sowing_id');

        // Filtrar registros por pond_code_id y sowing_id
        $registros = Mortality::where('pond_code_id', $pond_code_id)
            ->when($sowingId, function ($query, $sowingId) {
                return $query->where('sowing_id', $sowingId);
            })
            ->orderBy('id', 'desc') // 🔁 Ordenar por ID descendente
            ->get();

        // Generar PDF con los registros filtrados
        $pdf = Pdf::loadView('pdf.Mortality.pond_mortality', compact('registros', 'pond'));
        return $pdf->download('Mortalidad_Estanque.pdf');
    }


    public function pdfQuincena($pond_unit_code_id, $quincena)
    {
        $all = Mortality::where('pond_code_id', $pond_unit_code_id)->get();

        $grupo = $all->chunk(15)[$quincena - 1] ?? collect(); // obtener quincena específica
        $pond = pond_unit_code::find($pond_unit_code_id);

        $pdf = Pdf::loadView('pdf.Mortality.biweekly_mortality', compact('grupo', 'quincena', 'pond'));
        return $pdf->download("Mortalidad_Quincena_{$quincena}.pdf");
    }

    public function edit($id)
    {
        $mortalidad = Mortality::findOrFail($id);
        // Retornar la vista de edición (ajústala según tu proyecto)
        return view('auth.admin.Unit_registration.edit', compact('mortalidad'));
    }

    public function destroy($id)
    {
        $mortalidad = Mortality::findOrFail($id);

        $tiempoLimite = \Carbon\Carbon::parse($mortalidad->created_at)->addHours(24);
        $now = \Carbon\Carbon::now('UTC');

        if ($now->greaterThan($tiempoLimite)) {
            return redirect()->route('mortality.index')->with('error', 'Este registro ya no puede ser eliminado. Ha pasado el tiempo permitido de 24 horas.');
        }

        $mortalidad->delete();

        return redirect()->route('mortality.index')->with('success', 'Registro eliminado correctamente.');
    }

 public function update(Request $request, $id)
{
    $mortalidad = Mortality::findOrFail($id);

    $tiempoLimite = \Carbon\Carbon::parse($mortalidad->created_at)->addHours(24);
    $now = \Carbon\Carbon::now('UTC');

    if ($now->greaterThan($tiempoLimite)) {
        return redirect()->route('mortality.index')->with('error', 'Este registro ya no puede ser actualizado. Ha pasado el tiempo permitido de 24 horas.');
    }

    $request->validate([
        'datetime' => 'required|date',
        'amount' => 'required|integer|min:0',
        'observation' => 'nullable|string',
    ]);

    // Guardamos el valor anterior para calcular la diferencia
    $oldAmount = $mortalidad->amount;
    $newAmount = $request->amount;
    $diferencia = $newAmount - $oldAmount;

    $mortalidad->datetime = $request->datetime;
    $mortalidad->amount = $newAmount;
    $mortalidad->observation = $request->observation;
    $mortalidad->save();

    // Recalcular balances desde este registro en adelante
    $this->recalcularBalanceDesde($mortalidad);

    return redirect()->route('mortality.index')->with('success', 'Registro actualizado y balance recalculado correctamente.');
}
private function recalcularBalanceDesde(Mortality $mortalidad)
{
    $pondCodeId = $mortalidad->pond_code_id;
    $sowingId = $mortalidad->sowing_id;

    $sowing = Sowing::find($sowingId);
    if (!$sowing) return;

    // Traer todos los registros de ese estanque y siembra, ordenados por fecha
    $registros = Mortality::where('pond_code_id', $pondCodeId)
        ->where('sowing_id', $sowingId)
        ->orderBy('datetime')
        ->orderBy('id')
        ->get();

    $balance = $sowing->fish_count;

    foreach ($registros as $registro) {
        $balance -= $registro->amount;
        $registro->fish_balance = $balance;
        $registro->save();
    }
}

}
