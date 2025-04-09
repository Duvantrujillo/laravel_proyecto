<?php

namespace App\Http\Controllers;

use App\Models\observation;
use Illuminate\Http\Request;



class observationcontroller extends Controller
{
    public function create()
    {
        return view('auth.user.herramientas.formherramientas');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $observaciones = observation::all();

        return view('auth.user.herramientas.filtroherramienta', compact('observaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)

    {
        $request->validate([
            'amount' => 'required|integer',
            'product' =>  'required|string',
            'observation' => 'required|string'
        ]);
        
        if (observation::where('product', $request->product)->exists()) {
            return back()->with('error', 'esta herramienta ya existe no puedes agregarlo nuevamente');
        }


        observation::create([
            'amount' => $request->amount,
            'product' => $request->product,
            'observation' => $request->observation
        ]);

        return redirect()->route('observacion.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $observacion = observation::findOrFail($id);
        return view('auth.user.herramientas.filtroherramienta');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' =>'required|integer',
            'product' =>'required|string',
            'observation'=>'required|string'
        ]);
    
        if (observation::where('product', $request->product)->where('id', '!=', $id)->exists()) {
            return back()->with('update', 'No puede agregar este nombre porque ya existe');
        }
    
        // Asegurar que el registro existe antes de actualizar
        $observacion = observation::findOrFail($id);
    
        $observacion->update([
            'amount' => $request->amount,
            'product' => $request->product,
            'observation' => $request->observation
        ]);
    
        return redirect()->route('observacion.index')->with('correcto', 'La herramienta fue actualizada correctamente');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $observacion = observation::findOrfail($id);
        $observacion -> delete();

        return redirect()->route('observacion.index')->with('success','herramienta elimnada correctamente');
    }
}
