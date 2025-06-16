<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Este método se ejecuta cuando un usuario no autenticado intenta acceder
     */
    public function unauthenticated($request, AuthenticationException $exception)
    {
        // Si espera JSON (API), responde en JSON
        if ($request->expectsJson()) {
            return response()->json(['message' => 'No autenticado.'], 401);
        }

        // Redirige al login con un mensaje de error
        return redirect()->guest(route('login'))->with('error', 'Debes iniciar sesión para acceder.');
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
