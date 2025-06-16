<?php
namespace App\Http\Controllers;

use App\Models\Species;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function create()
    {
        $species = Species::all();
        return view('auth.admin.species.Type.Typespecie', compact('species'));
    }

   public function store(Request $request)
{
    $request->validate([
        'species_id' => 'required|exists:species,id',
        'name' => 'required|string|max:255|unique:types,name,NULL,id,species_id,' . $request->species_id,
    ], [
        'name.unique' => 'Este nombre de tipo ya está registrado para esta especie por favor usa otro.',
    ]);

    Type::create($request->all());
    return redirect()->route('types.create')->with('success', 'Tipo creado exitosamente.');
}

    public function destroy(Type $type)
    {
        $type->delete();
        return redirect()->route('species.index')->with('success', 'Tipo eliminado exitosamente.');
    }
}