<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Tool;

class LoanController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        $items = Tool::all();
        return view('auth.admin.Tool.Tool_control.tool_loans', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'quantity' => 'required|integer|min:1',
            'loan_date' => 'required|date',
            'requester_name' => 'required|string|max:255',
            'requester_id' => 'required|string|max:255',
        ]);

        $item = Tool::find($request->tool_id);

        if ($request->quantity > $item->amount) {
            return back()->with('error', 'Los Datos ingresados son incorrectos, no hay suficiente cantidad disponible.');
        }

        Loan::create([
            'tool_id' => $request->tool_id,
            'item' => $item->product,
            'quantity' => $request->quantity,
            'loan_date' => $request->loan_date,
            'requester_name' => $request->requester_name,
            'requester_id' => $request->requester_id,
            'delivered_by' => auth()->user()->name,
            'loan_status' => $request->loan_status,
        ]);

        $item->amount -= $request->quantity;
        $item->save();

        return redirect()->route('loans.create')->with('success', 'Herramienta prestada correctamente.');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
