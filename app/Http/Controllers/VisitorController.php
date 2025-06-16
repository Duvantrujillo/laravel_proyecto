<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\Visitor;

class VisitorController extends Controller
{
    public function index()
    {
        $visitors = Visitor::all();
        return view('auth.admin.visitors.index', compact('visitors'));
    }

    public function create()
    {
        return view('auth.admin.visitors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'document' => 'required',
            'phone' => 'required',
            'entry_date' => 'required|date',
            'entry_time' => 'required',
            'exit_time' => 'nullable',
            'email' => 'nullable|email',
            'origin' => 'nullable',
        ]);

        Visitor::create($request->all());

        return redirect()->route('visitors.index')->with('success', 'el registro de entrada se realizo correctamente.');

    }
    public function checkoutForm()
{
    $visitors = Visitor::whereNull('exit_time')->get();
    return view('auth.admin.visitors.checkout', compact('visitors'));
}



public function updateCheckout(Request $request)
{
    $request->validate([
        'exit_time' => 'required',
        'visitor_ids' => 'required|array',
    ]);

    Visitor::whereIn('id', $request->visitor_ids)->update([
        'exit_time' => $request->exit_time,
    ]);

    return redirect()->route('visitors.index')->with('success', 'el registro de salida se realizo correctamente.');
}


public function filter(Request $request)
{
    $query = Visitor::query();

    if ($request->filled('date')) {
        $query->where('entry_date', $request->date);
    }

    $visitors = $query->get();
    return view('auth.admin.visitors.index', compact('visitors'));
}


public function publicCreate()
{
    if (!Cache::get('formulario_publico_activo', true)) {
        return view('auth.admin.visitors.deactivated _form');
    }

    $a = rand(1, 10);
    $b = rand(1, 10);
    $pregunta = "¿Cuánto es $a + $b?";
    $respuesta = $a + $b;

    session(['pregunta_seguridad_texto' => $pregunta]);
    session(['pregunta_seguridad_respuesta' => $respuesta]);

    return view('auth.admin.visitors.public', ['pregunta' => $pregunta]);
}


public function publicStore(Request $request)
{
   
   
    $ip = $request->ip();
    $cacheKey = 'registro_visitante_ip_' . $ip;

    // 🚫 Límite por IP cada 24h
    if (Cache::has($cacheKey)) {
        return back()->withErrors([
            'error' => 'Ya realizaste un registro desde esta IP en las últimas 24 horas.'
        ])->withInput();
    }

    // 🐜 Honeypot
    if (!empty($request->input('website'))) {
        return back()->withErrors(['error' => 'Formulario inválido.']);
    }

    // ❓ Verificar pregunta anti-bot
    $respuestaEsperada = session('pregunta_seguridad_respuesta');
    if ((int)$request->input('pregunta_seguridad') !== (int)$respuestaEsperada) {
        return back()->withErrors(['pregunta_seguridad' => 'Respuesta incorrecta a la pregunta de seguridad.'])->withInput();
    }

    // ✅ Validación
    $request->validate([
        'name' => 'required',
        'document' => 'required',
        'phone' => 'required',
        'entry_date' => 'required|date',
        'entry_time' => 'required',
        'email' => 'nullable|email',
        'origin' => 'nullable',
    ]);

    // 💾 Guardar en base de datos
    Visitor::create($request->only([
        'name', 'document', 'phone', 'entry_date', 'entry_time', 'email', 'origin'
    ]));

    // 🕒 Guardar IP en cache por 24 horas
    Cache::put($cacheKey, true, now()->addHours(24));

    return redirect()->back()->with('success', 'Registro exitoso. ¡Gracias por tu visita!');
}






public function toggleFormState(Request $request)
{
    // Determina si el formulario debe ser activado o desactivado
    $nuevoEstado = $request->input('estado') === 'activar';

    // Actualiza el estado en la caché
    Cache::put('formulario_publico_activo', $nuevoEstado, now()->addDays(30));

    // Crea el mensaje según el nuevo estado
    $mensaje = $nuevoEstado 
        ? 'El formulario fue activado satisfactoriamente.' 
        : 'El formulario fue desactivado satisfactoriamente.';

    // Redirige con el mensaje de éxito
    return redirect()->route('visitors.index')->with('success', $mensaje);
}

}












