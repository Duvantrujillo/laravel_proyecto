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

        return view('auth.user.water_quality.create', compact('sowing'));
    }

    // Guardar registro de calidad de agua
    public function store(Request $request, Sowing $sowing)
    {
        if ($sowing->state !== 'inicializada') {
            return redirect()->back()->with('error', 'No se puede registrar calidad de agua porque la siembra está finalizada.');
        }

        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'ph' => 'required|numeric',
            'temperature' => 'required|numeric',
            'ammonia' => 'required|numeric',
            'turbidity' => 'required|numeric',
            'dissolved_oxygen' => 'required|numeric',
            'nitrites' => 'required|numeric',
            'nitrates' => 'required|numeric',
        ]);

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
        ]);

        return redirect()->route('water_quality.create', $sowing->id)
                         ->with('success', 'Registro guardado con éxito.');
    }

    // Mostrar historial de registros de calidad de agua para una siembra
    public function history($sowingId)
    {
        // Aquí agregamos el eager loading con 'user'
        $waterQualities = WaterQuality::where('sowing_id', $sowingId)
                            ->with('user')
                            ->orderBy('date', 'desc')
                            ->get();

        return view('auth.user.water_quality.history', compact('waterQualities', 'sowingId'));
    }
}
