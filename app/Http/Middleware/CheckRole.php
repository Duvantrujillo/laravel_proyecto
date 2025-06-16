<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            // Si no está autenticado, redirige al login con un mensaje flash
            return redirect()->route('login')->with('alert', 'Por favor, inicie sesión primero.');
        }

        if (!in_array(Auth::user()->role, $roles)) {
            // Si el rol no está permitido, aborta
            abort(403, 'No tienes permiso para acceder a esta ruta.');
        }

        return $next($request);
    }
}
