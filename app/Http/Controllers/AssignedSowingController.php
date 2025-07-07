<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Sowing;
use App\Models\AssignedSowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssignedSowingController extends Controller
{

public function index()
{
    // Elimina asignaciones sin siembra
    AssignedSowing::doesntHave('sowing')->delete();

    // Solo asignaciones donde la siembra esté en estado "inicializada"
    $assignments = AssignedSowing::with(['user', 'sowing'])
        ->whereHas('sowing', function ($query) {
            $query->where('state', 'inicializada');
        })
        ->get();

    $users = User::where('role', 'pasante')->get();
    $sowings = Sowing::where('state', 'inicializada')->get();

    return view('auth.admin.assigned_sowings.index', compact('assignments', 'users', 'sowings'));
}



public function create()
{
    $users = User::where('role', 'pasante')->get();

    // Solo mostrar las siembras que estén inicializadas
    $sowings = Sowing::with(['species', 'identifier.pond'])
                     ->where('state', 'inicializada')
                     ->get();

    return view('auth.admin.assigned_sowings.create', compact('users', 'sowings'));
}


public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'sowing_id' => 'required|exists:sowing,id',
    ]);

    $exists = AssignedSowing::where('user_id', $request->user_id)
        ->where('sowing_id', $request->sowing_id)
        ->exists();

    if ($exists) {
        return back()->with('error', 'Este usuario ya tiene asignada esta cosecha.');
    }

    AssignedSowing::create([
        'user_id' => $request->user_id,
        'sowing_id' => $request->sowing_id,
    ]);

    return redirect()->back()->with('success', 'Asignación realizada correctamente.');
}

public function destroy($id)
{
    $assignment = AssignedSowing::findOrFail($id);
    $assignment->delete();

    return back()->with('success', 'Cosecha desasignada correctamente.');
}


}
