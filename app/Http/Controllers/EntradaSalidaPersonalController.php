<?php

namespace App\Http\Controllers;

use App\Models\entrada_salida_personal;
use Illuminate\Http\Request;
use App\Models\grupos_personal;
use App\Models\register_personal;

class EntradaSalidaPersonalController extends Controller
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

        return view('auth.user.r-personal.r-entrada-salida.r-entrada-salida', compact('grupos'));
    }
    public function getUsuariosPorGrupo(Request $request)
    {
        // Validar que el grupo esté presente y exista
        $request->validate([
            'grupo' => 'required|exists:grupos_personal,id', // Usamos 'grupo' como clave foránea
        ]);

        // Obtener el grupo seleccionado
        $grupo = $request->grupo;

        // Filtrar los usuarios por el grupo seleccionado
        $usuarios = register_personal::where('grupo', $grupo)->get(); // Filtrar por 'grupo'

        // Devolver los usuarios en formato JSON
        return response()->json($usuarios);
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
        'grupo' => 'required|exists:grupos_personal,id', // Validar que el grupo existe
        'usuario' => 'required|exists:register_personal,id', // Validar que el usuario existe
        'entrada' => 'required|date', // Validar la fecha y hora de entrada
        'salida' => 'required|date|after_or_equal:entrada', // La salida debe ser después o igual a la entrada
        'visitó_granja' => 'required|boolean', // Validar si visitó la granja
    ]);

    // Crear el registro de entrada/salida
    try {
        // Crear una nueva entrada/salida
        $entradaSalida = new entrada_salida_personal();
        $entradaSalida->fecha_hora_ingreso = $request->entrada;
        $entradaSalida->fecha_hora_salida = $request->salida;
        $entradaSalida->visito_ultimas_48h = $request->visitó_granja;
        $entradaSalida->nombre = $request->usuario; // Aquí el campo 'nombre' se refiere al ID del usuario

        // Guardar el registro
        $entradaSalida->save();

        // Redirigir con mensaje de éxito
        return redirect()->route('entrada_salida.create')->with('success', 'Registro de entrada/salida guardado correctamente.');
    } catch (\Exception $e) {
        // En caso de error, redirigir con mensaje de error
        return redirect()->back()->withErrors(['error' => 'Hubo un error al guardar el registro.']);
    }
}



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\entrada_salida_personal  $entrada_salida_personal
     * @return \Illuminate\Http\Response
     */
    public function show(entrada_salida_personal $entrada_salida_personal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\entrada_salida_personal  $entrada_salida_personal
     * @return \Illuminate\Http\Response
     */
    public function edit(entrada_salida_personal $entrada_salida_personal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\entrada_salida_personal  $entrada_salida_personal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, entrada_salida_personal $entrada_salida_personal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\entrada_salida_personal  $entrada_salida_personal
     * @return \Illuminate\Http\Response
     */
    public function destroy(entrada_salida_personal $entrada_salida_personal)
    {
        //
    }
}
