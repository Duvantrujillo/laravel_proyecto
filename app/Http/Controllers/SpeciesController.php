<?php

namespace App\Http\Controllers;

use App\Models\Species;
use Illuminate\Http\Request;
use App\Models\Type;


class SpeciesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $species = Species::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })->with('types')->paginate(10);
        return view('auth.admin.species.species_filter.Species_filter', compact('species', 'search'));
    }

    public function create()
    {
        return view('auth.admin.species.Formspecies');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:species',
        ], [
            'name.unique' => 'Este nombre de especie ya existe.',
        ]);

        Species::create($request->all());
        return redirect()->route('species.create')->with('success', 'especie creada exitosamente.');
    }
    public function update(Request $request, Species $species)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:species,name,' . $species->id,
        ], [
            'name.unique' => 'Este nombre de especie ya existe.',
        ]);

        $species->update($request->all());
        return redirect()->route('species.index')->with('success', 'especie actualizada exitosamente.');
    }

    public function updateType(Request $request, Type $type)
    {
        $request->validate([
            'species_id' => 'required|exists:species,id',
            'name' => 'required|string|max:255|unique:types,name,' . $type->id . ',id,species_id,' . $request->species_id,
        ],[
        'name.unique' => 'Este nombre de tipo ya está registrado para esta especie.',
    ]);

        $type->update($request->all());
        return redirect()->route('species.index')->with('success', 'tipo actualizado exitosamente.');
    }

    public function storeType(Request $request)
    {
        $request->validate([
            'species_id' => 'required|exists:species,id',
            'name' => 'required|string|max:255|unique:types,name,NULL,id,species_id,' . $request->species_id,
        ]);
        Type::create($request->all());
        return redirect()->route('species.index')->with('success', 'tipo creado exitosamente.');
    }
public function destroy(Species $species)
{
    // Verificar si tiene tipos asociados
    if ($species->types()->exists()) {
        return redirect()->route('species.index')
            ->with('error', '❌ No se puede eliminar la especie porque tiene tipos asociados.');
    }

    $species->delete();

    return redirect()->route('species.index')->with('success', '✅ Especie eliminada exitosamente.');
}

}
