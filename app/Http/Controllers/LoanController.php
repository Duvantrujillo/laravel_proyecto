<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\observation;
use App\Models\Loan;


class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = Observation::all();
        return view('auth.user.herramientas.tool_loans.create', compact('items'));
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
            'full_name' => 'required|string|max:255',
            'observation_id' => 'required|exists:observations,id',
            'quantity' => 'required|integer|min:1',
            'loan_date' => 'required|date',
            'requester_name' => 'required|string|max:255',
            'requester_id' => 'required|string|max:255',
        ]);

        // Buscar herramienta
        $item = Observation::find($request->observation_id);

        // Validar si hay suficiente cantidad
        if ($request->quantity > $item->amount) {
            return back()->with('error', 'Los Datos ingresados son incorrectos, no hay suficiente cantidad disponible.');
      
        }

        // Crear el préstamo
        Loan::create([
            'full_name' => $request->full_name,
            'observation_id' => $request->observation_id,
            'item' => $item->product,
            'quantity' => $request->quantity,
            'loan_date' => $request->loan_date,
            'requester_name' => $request->requester_name,
            'requester_id' => $request->requester_id,
            'delivered_by' => auth()->user()->name,
            'loan_status' => $request->loan_status,
        ]);

        // Restar cantidad en inventario
        $item->amount -= $request->quantity;
        $item->save();

        return redirect()->route('loans.create')->with('success', 'Loan registered successfully.');
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
