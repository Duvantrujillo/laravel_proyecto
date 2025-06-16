<?php

namespace App\Http\Controllers;

use App\Models\observation;
use Illuminate\Http\Request;

class observationcontroller extends Controller
{
    public function create()
    {
        return view('auth.admin.herramientas.formherramientas');
    }

    public function index()
    {
        $observaciones = observation::all();
        return view('auth.admin.herramientas.filtroherramienta', compact('observaciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer',
            'product' =>  'required|string',
            'observation' => 'required|string'
        ]);
        
        if (observation::where('product', $request->product)->exists()) {
            return back()->with('error', 'Esta herramienta ya existe, no puedes agregarla nuevamente');
        }

        observation::create([
            'amount' => $request->amount,
            'product' => $request->product,
            'observation' => $request->observation
        ]);

        return redirect()->route('observacion.create')->with('success', 'La herramienta fue creada correctamente');
    }

    public function edit($id)
    {
        $observacion = observation::findOrFail($id);
        return view('auth.admin.herramientas.filtroherramienta');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|integer',
            'product' => 'required|string',
            'observation' => 'required|string'
        ]);

        if (observation::where('product', $request->product)->where('id', '!=', $id)->exists()) {
            return back()->withInput()->with('update', 'Este nombre ya está en uso. Usa otro nombre.');
        }

        $observacion = observation::findOrFail($id);

        $observacion->update([
            'amount' => $request->amount,
            'product' => $request->product,
            'observation' => $request->observation
        ]);

        return redirect()->route('observacion.index')->with('correcto', 'La herramienta fue actualizada correctamente');
    }

    public function destroy($id)
    {
        $observacion = observation::findOrfail($id);
        $observacion->delete();

        return redirect()->route('observacion.index')->with('eliminada', 'Herramienta eliminada correctamente');
    }
}
