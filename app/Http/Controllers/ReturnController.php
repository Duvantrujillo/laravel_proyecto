<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Observation;
use App\Models\ReturnModel;
use App\Models\Tool;
use Carbon\Carbon;

class ReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtener todas las devoluciones ordenadas por fecha descendente
        $returns = ReturnModel::with('loan')
                    ->orderBy('return_date', 'desc')
                    ->get();

        // Préstamos activos (cantidad pendiente > 0)
        $activeLoans = Loan::whereColumn('quantity', '>', 'returned_quantity')
                        ->orderBy('loan_date', 'desc')
                        ->get();

        // Devoluciones completadas (cantidad prestada = cantidad devuelta)
        $completedReturns = ReturnModel::whereHas('loan', function($query) {
                                $query->whereColumn('quantity', 'returned_quantity');
                            })
                            ->with('loan')
                            ->orderBy('return_date', 'desc')
                            ->get();

        return view('auth.admin.Tool.Tool_control.Record', 
            compact('returns', 'activeLoans', 'completedReturns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $loans = Loan::with('tool')->get();
        return view('auth.admin.Tool.Tool_control.return_of_tools', compact('loans'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
public function store(Request $request)
{
    // Validamos sin la regla 'after_or_equal:now' para controlar manualmente
    $request->validate([
        'loan_id' => 'required|exists:loans,id',
        'returned_quantity' => 'required|integer|min:1',
        'return_date' => 'required|date',
        'return_status' => 'nullable|string|max:1000',
        'img' => 'nullable|image|max:2048'
    ]);

    // Parseamos la fecha recibida y la fecha actual del servidor
    $returnDate = Carbon::parse($request->return_date);
    $now = now();

    // Permitir hasta 5 minutos atrás para evitar errores por diferencia horaria o segundos
    if ($returnDate->lt($now->subMinutes(5))) {
        return back()->withErrors(['return_date' => 'La fecha de devolución no puede ser menor a 5 minutos atrás respecto a la hora actual.']);
    }

    $loan = Loan::find($request->loan_id);
    $pending = $loan->quantity - $loan->returned_quantity;

    if ($request->returned_quantity > $pending) {
        return back()->withErrors(['returned_quantity' => 'La cantidad devuelta excede la cantidad pendiente.']);
    }

    $imge_path = null;
    if ($request->hasFile('img') && $request->file('img')->isValid()) {
        $imge_path = $request->file('img')->store('Tool_return', 'public');
    }

    ReturnModel::create([
        'loan_id' => $loan->id,
        'quantity_returned' => $request->returned_quantity,
        'return_date' => $returnDate,
        'return_status' => $request->return_status ?? 'Devuelto',
        'imge_path' => $imge_path,
        'received_by' => auth()->id(),
    ]);

    $loan->returned_quantity += $request->returned_quantity;

    if ($loan->quantity == $loan->returned_quantity) {
        if (!str_contains($loan->loan_status, 'Completado')) {
            $loan->loan_status .= ' - Completado';
        }
    }

    $loan->save();

    $item = Tool::find($loan->tool_id);
    $item->amount += $request->returned_quantity;
    $item->save();

    return redirect()->route('returns.create')
           ->with('success', 'Devolución registrada exitosamente.');
}

    // Métodos show, edit, update, destroy quedan igual...
    public function show($id) { }
    public function edit($id) { }
    public function update(Request $request, $id) { }
    public function destroy($id) { }
}
