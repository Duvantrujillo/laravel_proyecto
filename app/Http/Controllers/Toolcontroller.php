<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function create()
    {
        return view('auth.admin.Tool.register_tools');
    }

    public function index()
    {
        $tools = Tool::all();
        return view('auth.admin.Tool.Tool _warehouse', compact('tools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer',
            'product' => 'required|string',
            'observation' => 'required|string'
        ]);
        
        if (Tool::where('product', $request->product)->exists()) {
            return back()->with('error', 'Esta herramienta ya existe, no puedes agregarla nuevamente');
        }

        Tool::create([
            'amount' => $request->amount,
            'product' => $request->product,
            'observation' => $request->observation
        ]);

        return redirect()->route('Tool.create')->with('success', 'La herramienta fue creada correctamente');
    }

    public function edit($id)
    {
        $tool = Tool::findOrFail($id);
        // Asumo que aquí deberías enviar el tool a la vista para editar
        return view('auth.admin.herramientas.editherramienta', compact('tool'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|integer',
            'product' => 'required|string',
            'observation' => 'required|string'
        ]);

        if (Tool::where('product', $request->product)->where('id', '!=', $id)->exists()) {
            return back()->withInput()->with('update', 'Este nombre ya está en uso. Usa otro nombre.');
        }

        $tool = Tool::findOrFail($id);

        $tool->update([
            'amount' => $request->amount,
            'product' => $request->product,
            'observation' => $request->observation
        ]);

        return redirect()->route('Tool.index')->with('correcto', 'La herramienta fue actualizada correctamente');
    }

public function destroy($id)
{
    try {
        $tool = Tool::findOrFail($id);

        // Validar si tiene préstamos asociados
        if ($tool->loans()->exists()) {
            return redirect()->route('Tool.index')
                ->with('error', '❌ No se puede eliminar la herramienta porque tiene préstamos registrados.');
        }

        $tool->delete();

        return redirect()->route('Tool.index')
            ->with('eliminada', '✅ Herramienta eliminada correctamente');
    } catch (\Exception $e) {
        \Log::error('Error al eliminar herramienta: ' . $e->getMessage());
        return redirect()->route('Tool.index')
            ->with('error', '❌ Ocurrió un error al intentar eliminar la herramienta.');
    }
}

}
