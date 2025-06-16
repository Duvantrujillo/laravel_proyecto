<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login'); // Asegúrate que esta vista exista
    }

    public function login(Request $request)
    {
        // Validar credenciales
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = $request->input('email');
        $throttleKey = 'login-attempts:' . $email;

        // Verificar límite de intentos
        if (RateLimiter::tooManyAttempts($throttleKey, 10)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Demasiados intentos fallidos. Intenta de nuevo en $seconds segundos."
            ])->onlyInput('email');
        }

        // Intentar autenticación
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            $user = Auth::user();

            $state = strtolower(trim(preg_replace('/[\s\t\n\r]+/', '', $user->state)));

            if ($state === 'bloqueado') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Tu cuenta está bloqueada. Contacta al administrador.'
                ])->onlyInput('email');
            }


            if ($state === 'activo') {
                return redirect()->route($user->role === 'admin' ? 'admin.dashboard' : 'pasante.dashboard');
            }

            Auth::logout();
            return back()->withErrors([
                'email' => 'Estado de cuenta desconocido. Contacta al administrador.'
            ])->onlyInput('email');
        }

        // Si falla la autenticación, contar el intento fallido
        RateLimiter::hit($throttleKey, 60); // bloquea 10 intentos en 60 segundos

        return back()->withErrors([
            'email' => 'Correo o contraseña incorrectos.'
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }
}
