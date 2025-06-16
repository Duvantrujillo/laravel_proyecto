<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Mostrar todos los usuarios registrados
   public function index(Request $request)
{
    $query = User::query();

    if ($request->filled('document')) {
        $query->where('document', 'like', '%' . $request->document . '%');
    }

    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    if ($request->filled('state')) {
        $query->where('state', $request->state);
    }

    $users = $query->orderBy('created_at', 'desc')->get();


    return view('auth.admin.intern.index', compact('users'));
}

    // Mostrar formulario de registro
    public function create()
    {
        return view('auth.admin.intern.register');
    }

    // Procesar registro nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'document' => 'required|string|max:20|unique:users,document',
            'phone' => 'required|string|max:20',
            'state' => 'required|in:activo,inactivo,bloqueado',
            'role' => 'required|in:admin,pasante',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'document.required' => 'El documento es obligatorio.',
            'document.unique' => 'El documento ya está registrado.',
            'phone.required' => 'El teléfono es obligatorio.',
            'state.required' => 'El estado es obligatorio.',
            'state.in' => 'El estado seleccionado no es válido.',
            'role.required' => 'El rol es obligatorio.',
            'role.in' => 'El rol seleccionado no es válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        // Generar email: nombre.apellido(últimos 3 dígitos del documento)@senaacuicultura.com
        $firstName = strtolower(preg_replace('/\s+/', '', explode(' ', trim($request->name))[0]));
        $lastName = strtolower(preg_replace('/\s+/', '', explode(' ', trim($request->last_name))[0]));
        $lastThreeDigits = substr(preg_replace('/\D/', '', $request->document), -3);
        $email = "{$firstName}.{$lastName}{$lastThreeDigits}@senaacuicultura.com";

        $user = new User();
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->document = $request->document;
        $user->phone = $request->phone;
        $user->state = $request->state;
        $user->role = $request->role;
        $user->email = $email;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('users.create')->with('success', [
            'email' => $email,
            'password' => $request->password,
        ]);
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('auth.admin.intern.edit', compact('user'));
    }

    // Actualizar usuario
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'document' => 'required|string|max:20|unique:users,document,' . $user->id,
            'phone' => 'required|string|max:20',
            'state' => 'required|in:activo,inactivo,bloqueado',
            'role' => 'required|in:admin,pasante',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'document.required' => 'El documento es obligatorio.',
            'document.unique' => 'El documento ya está registrado.',
            'phone.required' => 'El teléfono es obligatorio.',
            'state.required' => 'El estado es obligatorio.',
            'state.in' => 'El estado seleccionado no es válido.',
            'role.required' => 'El rol es obligatorio.',
            'role.in' => 'El rol seleccionado no es válido.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->document = $request->document;
        $user->phone = $request->phone;
        $user->state = $request->state;
        $user->role = $request->role;

        $newPassword = null;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $newPassword = $request->password;
        }

        $user->save();

        if ($newPassword) {
            return redirect()->route('users.index')->with('success', [
                'message' => 'Usuario actualizado correctamente.',
                'password' => $newPassword,
            ]);
        }

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    // Eliminar usuario
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
