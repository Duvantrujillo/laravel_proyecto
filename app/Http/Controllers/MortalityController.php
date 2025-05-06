<?php

namespace App\Http\Controllers;

use App\Models\Mortality;
use App\Models\pond_unit_code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MortalityController extends Controller
{
    public function create()
    {
        $lakes = \App\Models\GeoPond::all(); // Listamos los lagos
        return view('auth.user.Unit_registration.Mortality.form', compact('lakes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'datetime' => 'required|date',
            'amount' => 'required|integer|min:0',
            'fish_balance' => 'required|integer|min:0',
            'observation' => 'nullable|string',
            'pond_code_id' => 'required|exists:pond_unit_codes,id',
        ]);

        Mortality::create([
            'datetime' => $request->datetime,
            'amount' => $request->amount,
            'fish_balance' => $request->fish_balance,
            'observation' => $request->observation,
            'pond_code_id' => $request->pond_code_id,
            'user_id' => Auth::id(), // <- Aquí se guarda el id del usuario logueado
        ]);

        return redirect()->back()->with('success', 'Registro de mortalidad guardado exitosamente.');
    }

    public function getPondsByPondId(Request $request)
    {
        $ponds = pond_unit_code::where('pond_id', $request->pond_id)->get();
        return response()->json($ponds);
    }
    public function index()
    {
        $filtro = Mortality::with(['user', 'pondUnitCode'])->get();
        return view('auth.user.Unit_registration.Mortality.Filtro', compact('filtro'));
    }
    
}

