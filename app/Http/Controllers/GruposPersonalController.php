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
        return view('auth.admin.r-personal.r-grupo.form', compact('grupos'));
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

    // Mostrar formulario para editar grupo
    public function editGrupo($id)
    {
        $grupo = grupos_personal::findOrFail($id);
        return view('auth.admin.r-personal.r-grupo.edit-grupo', compact('grupo'));
    }

    // Actualizar grupo
    public function updateGrupo(Request $request, $id)
    {
        $grupo = grupos_personal::findOrFail($id);

        $request->validate([
            'nombre' => 'required|regex:/^[a-zA-Z\s]+$/|unique:grupos_personal,nombre,' . $grupo->id . '|max:255',
        ], [
            'nombre.required' => 'El nombre del Tecnólogo es obligatorio.',
            'nombre.regex' => 'El nombre solo debe contener letras y espacios.',
            'nombre.unique' => 'Ya existe un Tecnólogo con ese nombre.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
        ]);

        $grupo->nombre = strtoupper($request->nombre);
        $grupo->save();

        return redirect()->route('grupo.create')->with('success', 'Tecnólogo actualizado con éxito');
    }

    // Mostrar formulario para editar ficha
    public function editFicha($id)
    {
        $ficha = Ficha::findOrFail($id);
        $grupos = grupos_personal::all();
        return view('auth.admin.r-personal.r-grupo.edit-ficha', compact('ficha', 'grupos'));
    }

    // Actualizar ficha
    public function updateFicha(Request $request, $id)
    {
        $ficha = Ficha::findOrFail($id);

        $request->validate([
            'nombre' => 'required|numeric|integer|min:0|unique:fichas,nombre,' . $ficha->id,
            'grupo_id' => 'required|exists:grupos_personal,id',
        ], [
            'nombre.required' => 'El número de ficha es obligatorio.',
            'nombre.numeric' => 'La ficha debe ser un número.',
            'nombre.integer' => 'La ficha debe ser un número entero.',
            'nombre.min' => 'La ficha debe ser un número positivo.',
            'nombre.unique' => 'Ya existe una ficha con ese número.',
            'grupo_id.required' => 'Debe seleccionar un Tecnologo.',
            'grupo_id.exists' => 'El Tecnologo seleccionado no existe.',
        ]);

        $ficha->nombre = $request->nombre;
        $ficha->grupo_id = $request->grupo_id;
        $ficha->save();

        return redirect()->route('grupo.create')->with('success', 'Ficha actualizada con éxito');
    }



    // Eliminar ficha individual
public function destroyFicha($id)
{
    $ficha = Ficha::findOrFail($id);
    $ficha->delete();

    return redirect()->route('grupo.create')->with('success', 'Ficha eliminada con éxito');
}

// Eliminar grupo con todas sus fichas
public function destroyGrupo($id)
{
    $grupo = grupos_personal::findOrFail($id);

    // Eliminar todas las fichas asociadas
    $grupo->fichas()->delete();

    // Eliminar el grupo
    $grupo->delete();

    return redirect()->route('grupo.create')->with('success', 'Grupo y sus fichas eliminados con éxito');
}

}
