<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario está autenticado
        if (Auth::check()) {
            // Verificar si el usuario es administrador
            if (Auth::user()->is_admin) {
                return $next($request);
            } else {
                // Si el usuario no es administrador, redirigir con mensaje de error
                return redirect('/')->with('error', 'Acceso denegado. Solo para administradores.');
            }
        }

        // Si el usuario no está autenticado, redirigir al login
        return redirect()->route('login')->with('error', 'Por favor, inicia sesión para acceder.');
    }
}

