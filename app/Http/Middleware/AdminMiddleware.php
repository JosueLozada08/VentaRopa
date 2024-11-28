<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    /**
     * Manejar una solicitud entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            Log::warning('Acceso denegado. Usuario no autenticado intentó acceder a: ' . $request->fullUrl());
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a esta sección.');
        }

        // Verificar si el usuario tiene el atributo is_admin y es administrador
        if (!Auth::user() || !Auth::user()->is_admin) {
            Log::warning('Acceso denegado. Usuario no autorizado (ID: ' . Auth::id() . ') intentó acceder a: ' . $request->fullUrl());
            return redirect()->route('login')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        // Registrar acceso exitoso
        Log::info('Acceso concedido al usuario administrador (ID: ' . Auth::id() . ') en: ' . $request->fullUrl());

        return $next($request);
    }
}
