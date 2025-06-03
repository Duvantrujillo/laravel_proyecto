<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Observation;
use App\Models\ReturnModel;
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

        return view('auth.user.herramientas.tool_loans.filter', 
            compact('returns', 'activeLoans', 'completedReturns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $loans = Loan::with('observation')->get();
        return view('auth.user.herramientas.tool_loans.index', compact('loans'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'returned_quantity' => 'required|integer|min:1',
            'return_date' => 'required|date',
            'return_status' => 'nullable|string|max:1000',
        ]);

        $loan = Loan::find($request->loan_id);
        $pending = $loan->quantity - $loan->returned_quantity;

        if ($request->returned_quantity > $pending) {
            return back()->withErrors(['returned_quantity' => 'La cantidad devuelta excede la cantidad pendiente.']);
        }

        // Crear registro de devolución
        ReturnModel::create([
            'loan_id' => $loan->id,
            'quantity_returned' => $request->returned_quantity,
            'return_date' => $request->return_date,
            'return_status' => $request->return_status ?? 'Devuelto',
            'received_by' => auth()->user()->name,
        ]);

        // Actualizar cantidad devuelta en el préstamo
        $loan->returned_quantity += $request->returned_quantity;
        
        // Actualizar estado si se completó la devolución
        if ($loan->quantity == $loan->returned_quantity) {
            $loan->loan_status = 'Completado';
        }
        
        $loan->save();

        // Actualizar inventario de herramientas
        $item = Observation::find($loan->observation_id);
        $item->amount += $request->returned_quantity;
        $item->save();

        return redirect()->route('returns.create')
               ->with('success', 'Devolución registrada exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}