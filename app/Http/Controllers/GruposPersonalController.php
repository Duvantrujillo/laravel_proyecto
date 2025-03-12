<?php

namespace App\Http\Controllers;

use App\Models\grupos_personal;
use Illuminate\Http\Request;

class GruposPersonalController extends Controller
{
    public function create()
    {
        // Obtener todos los grupos desde la base de datos
       
    }

    public function store(Request $request)
    {
        // Validamos los datos del formulario
        $request->validate([
            'nombre' => 'required',
            'ficha' => 'nullable|string'
        ]);

        // Creamos un nuevo grupo y lo guardamos en la base de datos
        $grupo = new grupos_personal();
        $grupo->nombre = $request->nombre;
        $grupo->numero_ficha = $request->ficha;
        $grupo->save();

        return redirect()->route('grupo-form')->with('success', 'Grupo registrado con éxito');
    }
}
