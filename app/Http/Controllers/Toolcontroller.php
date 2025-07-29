<?php

namespace App\Http\Controllers;
use App\Models\Loan;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'observation' => 'required|string',
            'image' => 'nullable|image|max:2048',   // Validar imagen opcional, max 2MB
            'extra_info' => 'nullable|string|max:400',
            'status' => 'required|in:enabled,disabled',
        ],[
            'extra_info.max' => 'porfavor la informacion de la herramientas debe ser menor a 400 letras',
        ]    
    );

        if (Tool::where('product', $request->product)->exists()) {
            return back()->with('error', 'Esta herramienta ya existe, no puedes agregarla nuevamente');
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            // Guarda la imagen en storage/app/public/tools
            $imagePath = $request->file('image')->store('tools', 'public');
        }

        Tool::create([
            'amount' => $request->amount,
            'total_quantity' => $request->amount,
            'product' => $request->product,
            'observation' => $request->observation,
            'image_path' => $imagePath,
            'extra_info' => $request->extra_info,
            'status' => $request->status,
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
            'observation' => 'required|string',
            'extra_info' => 'nullable|string|max:400',
            'status' => 'required|in:enabled,disabled',
            'image' => 'nullable|image|max:2048',
        ],[
            'extra_info.max'=>'porfavor en la informacion de herramientas debe de ser menos de 400 letras',
        ]
    
    );

        
       $hasPendiLoans = Loan::where('tool_id',$id)->
       whereColumn('returned_quantity','<','quantity')->exists();

       if ($hasPendiLoans){
        return back()->withInput()->with('pendigLoans','porfavor primero devuelve primero las cantidades prestadas');
       }
        
        if (Tool::where('product', $request->product)->where('id', '!=', $id)->exists()) {
            return back()->withInput()->with('update', 'Este nombre ya está en uso. Usa otro nombre.');
        }

        $tool = Tool::findOrFail($id);

        $data = [
            'amount' => $request->amount,
            'total_quantity' => $request->amount,
            'product' => $request->product,
            'observation' => $request->observation,
            'extra_info' => $request->extra_info,
            'status' => $request->status,
        ];


        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior si existe
            if ($tool->image_path) {
                Storage::disk('public')->delete($tool->image_path);
            }

            $data['image_path'] = $request->file('image')->store('tools', 'public');
        }

        $tool->update($data);

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
