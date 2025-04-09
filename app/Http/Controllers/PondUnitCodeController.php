<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeoPond;
use App\Models\pond_unit_code;

class PondUnitCodeController extends Controller
{
    public function index(){
        $filtros = GeoPond::all();
        return view('auth.admin.geo-estanque.filter',compact('filtros'));

    }

    public function create(){
        $filtros = GeoPond::all();
        return view('auth.admin.geo-estanque.geo-estanque',compact ('filtros'));
    }

    public function store(Request $request){
        $request->validate([
            'idficador' => 'required|integer',
            'pond_id' => 'required|exists:geoponds,id',
        ]);

        $existe = pond_unit_code::where('identificador', $request->idficador)
            ->where('pond_id', $request->pond_id)
            ->exists();

        if($existe){
            return redirect()->route('geo.create')->with('error','este numero ya esta en uso porfaro usa otro');
        };

        pond_unit_code::create([
            'identificador'=> $request->idficador,
            'pond_id'=> $request->pond_id,
        ]);
         return redirect()->route('geo.create')->with('succes','el registro fue exitoso');

    }

}
