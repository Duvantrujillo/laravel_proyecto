<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedWithRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
  public function handle(Request $request, Closure $next)
{
    if (Auth::check()) {
        $role = Auth::user()->role;
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'pasante':
                return redirect()->route('pasante.dashboard');
            default:
                return redirect()->route('home');
        }
    }

    return $next($request);
}

}