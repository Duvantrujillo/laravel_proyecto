<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use App\Models\grupos_personal;
use App\Models\Ficha;
use App\Models\register_personal;
use Illuminate\Http\Request;

class RegisterPersonalController extends Controller
{
    public function index(){
    }

    public function create()
    {
        $grupos = grupos_personal::all();
        return view('auth.user.r-personal.r-personal', compact('grupos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'numero_documento' => 'required|numeric|unique:register_personal,numero_documento',
            'numero_telefono' => 'required|digits:10',
            'correo' => 'required|email',
            'grupo' => 'required|exists:grupos_personal,id',
            'fichas' => 'required|exists:fichas,id',
        ], [
            'numero_documento.unique' => 'El número de documento ya está registrado.'
        ]);

        try {
            register_personal::create([
                'nombre' => ucwords(strtolower($request->nombre)),
                'numero_documento' => $request->numero_documento,
                'numero_telefono' => $request->numero_telefono,
                'correo' => $request->correo,
                'grupo' => $request->grupo,
                'fichas' => $request->fichas,
            ]);

            return redirect()->route('register.create')->with('success', 'Aprendiz registrado con éxito.');
        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['error' => 'Hubo un error al registrar los datos.']);
        }
    }

    public function getFichas(Request $request)
    {
        $grupoId = $request->grupo_id;
        $fichas = Ficha::where('grupo_id', $grupoId)->get();
        return response()->json($fichas);
    }

    public function checkNumeroDocumento(Request $request)
    {
        $exists = register_personal::where('numero_documento', $request->numero_documento)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function indexGruposFichas()
    {
        $grupos = grupos_personal::all();
        $fichasPorGrupo = Ficha::with('grupo')
            ->get()
            ->groupBy('grupo_id')
            ->map(function ($fichas) {
                return [
                    'grupo_nombre' => $fichas->first()->grupo->nombre ?? 'Sin grupo asignado',
                    'numeros' => $fichas->pluck('nombre')->all()
                ];
            });

        return view('auth.user.r-personal.r-grupo.grupos-fichas', compact('grupos', 'fichasPorGrupo'));
    }

    public function filtrarPersonal(Request $request)
    {
        $grupos = grupos_personal::all();
        $fichas = null;
        $personal = register_personal::query();

        if ($request->filled('grupo_id')) {
            $personal->whereHas('grupo', function ($query) use ($request) {
                $query->where('id', $request->grupo_id);
            });
            $fichas = Ficha::where('grupo_id', $request->grupo_id)->get();
        }

        if ($request->filled('ficha_id')) {
            $personal->where('fichas', $request->ficha_id);
        }

        $personal = $personal->with(['grupo', 'ficha'])->paginate(10);

        // Depuración opcional: Solo si necesitas inspeccionar los datos
        // Log::info('Personal encontrado:', ['data' => $personal->items()]);

        return view('auth.user.r-personal.personal-filtrado', compact('grupos', 'fichas', 'personal'));
    }
}