<?php

namespace App\Http\Controllers;

use App\Models\entrada_salida_personal;
use Illuminate\Http\Request;
use App\Models\grupos_personal;
use App\Models\register_personal;
use App\Models\Ficha;

class EntradaSalidaPersonalController extends Controller
{
    public function create()
    {
        $grupos = grupos_personal::all();
        return view('auth.user.r-personal.r-entrada-salida.r-entrada-salida', compact('grupos'));
    }

    public function getFichasPorGrupo(Request $request)
    {
        $request->validate([
            'grupo' => 'required|exists:grupos_personal,id',
        ]);

        $fichas = Ficha::where('grupo_id', $request->grupo)->get();
        return response()->json($fichas);
    }

    public function getUsuariosPorFicha(Request $request)
    {
        $request->validate([
            'ficha' => 'required|exists:fichas,id',
        ]);

        $usuarios = register_personal::where('fichas', $request->ficha)->get();
        return response()->json($usuarios);
    }

    public function store(Request $request)
    {
        $request->validate([
            'grupo' => 'required|exists:grupos_personal,id',
            'ficha' => 'required|exists:fichas,id',
            'usuarios' => 'required|array',
            'usuarios.*.selected' => 'nullable|boolean',
            'usuarios.*.entrada' => 'required_if:usuarios.*.selected,1|date',
            'usuarios.*.visito_granja' => 'required_if:usuarios.*.selected,1|boolean',
        ]);

        try {
            $usuarios = $request->input('usuarios', []);
            foreach ($usuarios as $usuarioId => $data) {
                if (isset($data['selected']) && $data['selected']) {
                    entrada_salida_personal::create([
                        'fecha_hora_ingreso' => $data['entrada'],
                        'fecha_hora_salida' => null, // Dejamos null ya que no se envía desde esta vista
                        'visito_ultimas_48h' => $data['visito_granja'],
                        'nombre' => $usuarioId, // Asumimos que 'nombre' es el ID del usuario en tu modelo
                        'grupo' => $request->grupo,
                        'ficha' => $request->ficha,
                    ]);
                }
            }

            return redirect()->route('entrada_salida.create')->with('success', 'Registros de entrada/salida guardados correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Hubo un error al guardar los registros: ' . $e->getMessage()]);
        }
    }

   
    // ... Otros métodos sin cambios ...

    // ... Otros métodos sin cambios ...

    public function index(Request $request)
    {
        $filtros = $request->only(['id', 'fecha_hora_ingreso', 'fecha_hora_salida', 'visito_ultimas_48h', 'nombre', 'grupo', 'ficha']);
    
        $query = entrada_salida_personal::with(['nombreRelacion', 'grupoRelacion', 'fichaRelacion']);
    
        foreach ($filtros as $campo => $valor) {
            if ($valor !== null && $valor !== '') {
                if ($campo === 'fecha_hora_ingreso' || $campo === 'fecha_hora_salida') {
                    $query->whereDate($campo, $valor);
                } elseif ($campo === 'visito_ultimas_48h') {
                    $query->where($campo, $valor);
                } elseif ($campo === 'grupo') {
                    $query->where('grupo', $valor); // Filtra por ID de grupo
                } elseif ($campo === 'ficha') {
                    $query->where('ficha', $valor); // Filtra por ID de ficha
                } elseif ($campo === 'nombre') {
                    // Filtrar por el nombre en la relación register_personal
                    $query->whereHas('nombreRelacion', function ($q) use ($valor) {
                        $q->where('nombre', 'like', "%$valor%");
                    });
                } else {
                    $query->where($campo, 'like', "%$valor%");
                }
            }
        }
    
        $query->whereNull('fecha_hora_salida');
    
        $registros = $query->get();
        $grupos = grupos_personal::all();
        $fichas = Ficha::all();
    
        return view('auth.user.r-personal.r-entrada-salida.salida', [
            'registros' => $registros,
            'filtros' => $filtros,
            'grupos' => $grupos,
            'fichas' => $fichas,
        ]);
    }

    // ... Otros métodos sin cambios ...


    // ... Otros métodos sin cambios ...

    
    public function actualizarFechaSalida(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:entrada_salida_personal,id',
            'fecha_salida' => 'required|date', // Acepta formato datetime
        ]);
    
        entrada_salida_personal::whereIn('id', $request->ids)
            ->update(['fecha_hora_salida' => $request->fecha_salida]);
    
        return redirect()->route('entrada_salida.index')->with('success', 'Fecha y hora de salida actualizadas correctamente.');
    }



  
    
  
        // ... Otros métodos existentes ...
    
        public function filtrarPorGrupo(Request $request)
        {
            $grupos = grupos_personal::all();
            $fichas = Ficha::all();
    
            $registros = entrada_salida_personal::query()
                ->with(['nombreRelacion', 'grupoRelacion', 'fichaRelacion']);
    
            // Filtro por grupo
            if ($request->filled('grupo_id')) {
                $registros->where('grupo', $request->grupo_id);
            }
    
            // Filtro por ficha
            if ($request->filled('ficha_id')) {
                $registros->where('ficha', $request->ficha_id);
            }
    
            // Filtro por fecha/hora de ingreso
            if ($request->filled('fecha_hora_ingreso')) {
                $registros->where('fecha_hora_ingreso', '>=', $request->fecha_hora_ingreso);
            }
    
            // Filtro por fecha/hora de salida
            if ($request->filled('fecha_hora_salida')) {
                $registros->where('fecha_hora_salida', '>=', $request->fecha_hora_salida);
            }
    
            // Filtro por visitó granja
            if ($request->filled('visito_ultimas_48h')) {
                $registros->where('visito_ultimas_48h', $request->visito_ultimas_48h);
            }
    
            // Filtro por nombre de persona
            if ($request->filled('nombre')) {
                $registros->whereHas('nombreRelacion', function ($query) use ($request) {
                    $query->where('nombre', 'like', '%' . $request->nombre . '%');
                });
            }
    
            $registros = $registros->orderBy('fecha_hora_ingreso', 'desc')->paginate(10);
    
            return view('auth.user.r-personal.r-entrada-salida.entradas-salidas-filtradas', compact('grupos', 'fichas', 'registros'));
        }
    

}