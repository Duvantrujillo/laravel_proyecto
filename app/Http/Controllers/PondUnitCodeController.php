<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeoPond;
use App\Models\pond_unit_code;

class PondUnitCodeController extends Controller
{
    // Mostrar la lista de estanques y sus identificadores
  public function index()
{
    // Obtener todas las geomembranas con sus identificadores relacionados (pueden estar vacíos)
    $geomembranas = GeoPond::with('identificadores')->get();

    $filtros2 = $geomembranas->map(function ($geo) {
        return [
            'pond_id' => $geo->id,
            'pond_name' => $geo->name,
            'identificadores' => $geo->identificadores->map(function ($id) {
                return [
                    'id' => $id->id,
                    'identificador' => $id->identificador,
                    'pond_id' => $id->pond_id,
                ];
            })->toArray()
        ];
    });

    return view('auth.admin.geo-estanque.filter', compact('filtros2'));
}

    // Mostrar formulario para crear identificador
    public function create()
    {
        $filtros = GeoPond::all();
        return view('auth.admin.geo-estanque.geo-estanque', compact('filtros'));
    }

    // Guardar nuevo identificador
    public function store(Request $request)
    {
        $request->validate([
            'idficador' => 'required|integer',
            'pond_id' => 'required|exists:geoponds,id',
        ]);

        $pond = GeoPond::find($request->pond_id);
        $nameParts = explode(' ', $pond->name);
        $pondType = $nameParts[0];

        $sameTypePondIds = GeoPond::where('name', 'like', $pondType . '%')->pluck('id')->toArray();

        $existe = pond_unit_code::where('identificador', $request->idficador)
            ->whereIn('pond_id', $sameTypePondIds)
            ->exists();

        if ($existe) {
            return redirect()->route('geo.create')
                ->with('error', 'Este número ya está en uso en ' . $pondType . ', por favor usa otro.');
        }

        pond_unit_code::create([
            'identificador' => $request->idficador,
            'pond_id' => $request->pond_id,
        ]);

        return redirect()->route('geo.create')->with('success', 'Registro exitoso.');
    }

    // Mostrar formulario para editar identificador
    public function edit($id)
    {
        $identificador = pond_unit_code::findOrFail($id);
        $estanques = GeoPond::all();
        return view('auth.admin.geo-estanque.edit', compact('identificador', 'estanques'));
    }

    // Actualizar identificador
 public function update(Request $request, $id)
{
    $request->validate([
        'idficador' => 'required|integer',
        'pond_id' => 'required|exists:geoponds,id',
    ]);

    // Validar si ese identificador ya existe en el mismo estanque y no sea el mismo registro
    $existe = pond_unit_code::where('identificador', $request->idficador)
        ->where('pond_id', $request->pond_id)
        ->where('id', '!=', $id)
        ->exists();

    if ($existe) {
        return back()->with('error', 'Este identificador ya está asignado en este estanque.');
    }

    $identificador = pond_unit_code::findOrFail($id);
    $identificador->update([
        'identificador' => $request->idficador,
        'pond_id' => $request->pond_id
    ]);

   return back()->with('success', '¡Identificador registrado correctamente!');

}


    // Eliminar identificador
   public function destroy($id)
{
    $identificador = pond_unit_code::findOrFail($id);

    // Verifica si tiene siembras asociadas
    if ($identificador->sowings()->exists()) {
        return redirect()->route('geo.index')
            ->with('error', '❌ No se puede eliminar el identificador porque tiene siembras asociadas.');
    }

    $identificador->delete();

    return redirect()->route('geo.index')->with('success', '✅ Identificador eliminado correctamente.');
}


    // Mostrar formulario para editar el nombre del estanque
    public function editEstanque($pond_id)
    {
        $pond = GeoPond::findOrFail($pond_id);
        return view('auth.admin.geo-estanque.edit-nombre', compact('pond'));
    }

    // Actualizar nombre del estanque
 public function updateEstanque(Request $request, $pond_id)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:geoponds,name,' . $pond_id,
    ], [
        'name.unique' => 'Este nombre ya está en uso. Por favor, elige otro.',
        'name.required' => 'El nombre del estanque es obligatorio.',
    ]);

    $pond = GeoPond::findOrFail($pond_id);
    $pond->name = $request->name;
    $pond->save();

    return back()->with('success', '¡Guardado correctamente!');

}

public function deleteEstanque($pond_id)
{
    $pond = GeoPond::findOrFail($pond_id);

    // Verificar si tiene identificadores asociados
    if ($pond->identificadores()->exists()) {
        return redirect()->route('geo.index')
            ->with('error', '❌ No se puede eliminar el estanque porque tiene identificadores asociados.');
    }

    // Si no tiene identificadores, se elimina
    $pond->delete();

    return redirect()->route('geo.index')
        ->with('success', '✅ Estanque eliminado correctamente.');
}



}
