<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerificarBloqueo
{
    public function handle($request, Closure $next)
    {
        // Si está autenticado y su estado es bloqueado
        if (Auth::check() && Auth::user()->state === 'bloqueado') {
            Auth::logout(); // Opcional: cerrar sesión para seguridad
            return redirect()->route('bloqueado');
        }

        return $next($request);
    }
}
