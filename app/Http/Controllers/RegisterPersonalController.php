<?php

namespace App\Http\Controllers;
use Illuminate\Database\QueryException;

use App\Models\grupos_personal;
use App\Models\register_personal;
use Illuminate\Http\Request;

class RegisterPersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $grupos = grupos_personal::all();
    
        return view('auth.user.r-personal.r-personal', compact('grupos'));
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'nombre' => 'required', 
            'numero_documento' => 'required|numeric|unique:register_personal,numero_documento',
            'numero_telefono' => 'required|digits:10',
            'correo' => 'required|email',
            'grupo' => 'required|exists:grupos_personal,id',
        ],[
            'numero_documento.unique' => 'El número de documento ya está registrado.' // Mensaje personalizado
        ]);
    
        try {
            // Intentar crear el registro
            register_personal::create($request->all());
    
            // Redirigir con mensaje de éxito
            return redirect()->route('register.create')->with('success', 'Recolección registrada con éxito.');
            
        } catch (QueryException $e) {
            // Manejar el error de duplicado de manera personalizada
            if ($e->getCode() == 23000) {
                return redirect()->back()->withErrors(['error' => 'El número de documento ya está registrado.']);
            }
    
            // Manejar otros errores
            return redirect()->back()->withErrors(['error' => 'Hubo un error al registrar los datos.']);
        }
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\register_personal  $register_personal
     * @return \Illuminate\Http\Response
     */
    public function show(register_personal $register_personal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\register_personal  $register_personal
     * @return \Illuminate\Http\Response
     */
    public function edit(register_personal $register_personal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\register_personal  $register_personal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, register_personal $register_personal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\register_personal  $register_personal
     * @return \Illuminate\Http\Response
     */
    public function destroy(register_personal $register_personal)
    {
        //
    }
}
