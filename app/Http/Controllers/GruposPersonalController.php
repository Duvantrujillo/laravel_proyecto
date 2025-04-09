<?php
namespace App\Http\Controllers;

use App\Models\Ficha;
use App\Models\grupos_personal;
use Illuminate\Http\Request;

class GruposPersonalController extends Controller
{
    // Método para mostrar el formulario de creación de grupos
    public function create()
    {
        $grupos = grupos_personal::all();
        return view('auth.user.r-personal.r-grupo.form', compact('grupos'));
    }

    // Método para guardar un nuevo grupo
    public function storeGrupo(Request $request)
    {
        $request->validate([
            'nombre' => 'required|regex:/^[a-zA-Z\s]+$/|unique:grupos_personal,nombre|max:255', // Solo texto, único
        ]);

        $grupo = new grupos_personal();
        // Guardar el nombre en mayúsculas
        $grupo->nombre = strtoupper($request->nombre);
        $grupo->save();

        return redirect()->route('grupo.create')->with('success', 'Tecnologo registrado con éxito');
    }

    // Método para guardar una nueva ficha
    public function storeFicha(Request $request)
    {
        $request->validate([
            'nombre' => 'required|numeric|integer|min:0|unique:fichas,nombre', // Solo números enteros positivos, único
            'grupo_id' => 'required|exists:grupos_personal,id',
        ]);

        $ficha = new Ficha();
        $ficha->nombre = $request->nombre;
        $ficha->grupo_id = $request->grupo_id;
        $ficha->save();

        return redirect()->route('grupo.create')->with('success', 'Ficha registrada con éxito');
    }

    // Método para verificar duplicados del nombre del grupo
    public function checkNombre(Request $request)
    {
        $exists = grupos_personal::where('nombre', strtoupper($request->nombre))->exists();
        return response()->json(['exists' => $exists]);
    }

    // Método para verificar duplicados del número de ficha
    public function checkNumeroFicha(Request $request)
    {
        $exists = Ficha::where('nombre', $request->nombre)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function getFichas(Request $request)
    {
        $request->validate([
            'grupo_id' => 'required|exists:grupos_personal,id',
        ]);

        $fichas = Ficha::where('grupo_id', $request->grupo_id)->get();
        return response()->json($fichas);
    }
}