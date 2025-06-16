<?php

namespace App\Http\Controllers;

use App\Models\pond_unit_code;
use App\Models\Sowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SowingController extends Controller
{
    public function create(Request $request)
    {
        // Obtener especies y estanques para el formulario
        $species = DB::table('species')->select('id', 'name as species_name')->get();


        $ponds = pond_unit_code::join('GeoPonds', 'pond_unit_codes.pond_id', '=', 'GeoPonds.id')
            ->select('pond_unit_codes.pond_id', 'GeoPonds.name as pond_name')
            ->groupBy('pond_unit_codes.pond_id', 'GeoPonds.name')
            ->get();

        // Traer todos los estanques

        // Si hay filtros aplicados, filtrarlos
        $query = Sowing::query();
        if ($request->has('species_id') && $request->species_id != '') {
            $query->where('species_id', $request->species_id);
        }
        if ($request->has('pond_id') && $request->pond_id != '') {
            $query->where('pond_id', $request->pond_id);
        }

        $sowings = $query->get(); // Obtener las siembras con los filtros aplicados


        return view('auth.admin.Encabezado_Ajuste_Dieta.form', compact('species', 'ponds', 'sowings'));
    }

    public function store(Request $request)
{

$camposNumericos = [
        'initial_biomass',
        'initial_feeding_frequency',
        'fish_count',
        'area',
        'initial_weight',
        'total_weight',
        'initial_density',
        ];
    
    foreach ($camposNumericos as $campo) {
        if ($request->has($campo)) {
            $valor = $request->input($campo);

            // Elimina puntos de miles y reemplaza coma decimal por punto
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);

            // Si es fish_count (entero) forzar int
            if ($campo === 'fish_count') {
                $valor = intval(floatval($valor));
            } else {
                $valor = floatval($valor);
            }

            // Sobrescribe el valor en la request
            $request->merge([$campo => $valor]);
        }
    }
    // Validación
    $validated = $request->validate([
        'sowing_date' => 'required|date',
        'initial_biomass' => 'required|numeric',
        'species_id' => 'required|exists:species,id',
        'type_id' => 'required|exists:types,id',
        'initial_feeding_frequency' => 'required|numeric',
        'fish_count' => 'required|integer',
        'origin' => 'required|string|max:255',
        'area' => 'required|numeric',
        'initial_weight' => 'required|numeric',
        'total_weight' => 'required|numeric',
        'initial_density' => 'required|numeric',
        'pond_id' => 'required|exists:pond_unit_codes,pond_id',
        'identifier_id' => 'required|exists:pond_unit_codes,id',

    ]);

    // 🚫 Verificar si ya hay una siembra en ese estanque e identificador con estado "inicializada"
    $exists = Sowing::where('pond_id', $validated['pond_id'])
        ->where('identifier_id', $validated['identifier_id'])
        ->where('state', 'inicializada')
        ->exists();

    if ($exists) {
        // Si ya hay un seguimiento en curso, redirigir con mensaje de error
        return redirect()->back()->withErrors([
            'registro_existente' => 'Este lago con este identificador ya tiene un seguimiento de dieta en curso (estado: inicializada).'
        ])->withInput(); // Mantiene los datos del formulario
    }

    // ✅ Crear el nuevo registro si no existe uno en estado inicializada
    Sowing::create([
        'sowing_date' => $validated['sowing_date'],
        'initial_biomass' => $validated['initial_biomass'],
        'species_id' => $validated['species_id'],
        'type_id' => $validated['type_id'],
        'initial_feeding_frequency' => $validated['initial_feeding_frequency'],
        'fish_count' => $validated['fish_count'],
        'origin' => $validated['origin'],
        'area' => $validated['area'],
        'initial_weight' => $validated['initial_weight'],
        'total_weight' => $validated['total_weight'],
        'initial_density' => $validated['initial_density'],
        'pond_id' => $validated['pond_id'],
        'identifier_id' => $validated['identifier_id'],
        'state' => 'inicializada',
    ]);

    return redirect()->route('siembras.create')->with('success', 'Registro de siembra creado exitosamente.');
}


    // AJAX para cargar tipos por especie
    public function getTypes($speciesId)
    {
        // Obtener los tipos de especies asociadas
        $types = DB::table('types')->where('species_id', $speciesId)->get();
        return response()->json($types);
    }

    // AJAX para cargar identificadores por id del estanque
    public function getIdentifiers($pondId)
    {
        // Obtener identificadores para un estanque específico
        $identifiers = pond_unit_code::where('pond_id', $pondId)
            ->select('id', 'identificador')
            ->get();

        return response()->json($identifiers);
    }
}
