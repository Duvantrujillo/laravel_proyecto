<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeoPond;
use App\Models\pond_unit_code;

class PondUnitCodeController extends Controller
{
    public function index(){
        // Obtener todos los registros de pond_unit_code y agruparlos por pond_id
        $pondUnitCodes = pond_unit_code::with('pond')->get()->groupBy('pond_id');
        $filtros2 = [];
        
        // Construir un array con el nombre del estanque y sus identificadores
        foreach ($pondUnitCodes as $pond_id => $codes) {
            $pond = $codes->first()->pond; // Obtener el estanque asociado
            $identificadores = $codes->pluck('identificador')->toArray(); // Obtener todos los identificadores
            $filtros2[] = [
                'pond_name' => $pond ? $pond->name : 'Sin nombre',
                'identificadores' => $identificadores,
            ];
        }

        return view('auth.user.geo-estanque.filter',compact('filtros2'));
    }

    public function create(){
        $filtros = GeoPond::all();
        return view('auth.user.geo-estanque.geo-estanque',compact ('filtros'));
    }

    public function store(Request $request){
        $request->validate([
            'idficador' => 'required|integer',
            'pond_id' => 'required|exists:geoponds,id',
        ]);

        // Obtener el estanque seleccionado
        $pond = GeoPond::find($request->pond_id);

        // Extraer el tipo de estanque del nombre (por ejemplo, "Lago 1" -> "Lago")
        $nameParts = explode(' ', $pond->name);
        $pondType = $nameParts[0]; // El tipo es la primera palabra (por ejemplo, "Lago", "Estanque")

        // Obtener los IDs de todos los estanques del mismo tipo
        $sameTypePondIds = GeoPond::where('name', 'like', $pondType . '%')
            ->pluck('id')
            ->toArray();

        // Verificar si el identificador ya existe en un estanque del mismo tipo
        $existe = pond_unit_code::where('identificador', $request->idficador)
            ->whereIn('pond_id', $sameTypePondIds)
            ->exists();

        if($existe){
            return redirect()->route('geo.create')
                ->with('error', 'este numero ya esta en uso en un ' . $pondType . ', porfaro usa otro');
        }

        // Si no existe, crear el registro
        pond_unit_code::create([
            'identificador'=> $request->idficador,
            'pond_id'=> $request->pond_id,
        ]);
        return redirect()->route('geo.create')->with('succes','el registro fue exitoso');
    }
}