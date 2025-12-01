<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthEmpleado
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si está autenticado con el guard 'empleado'
        if (Auth::guard('empleado')->check()) {
            return $next($request);
        }

        // Si no está autenticado y es AJAX, retornar 401
        if ($request->expectsJson()) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        // Si no está autenticado, redirigir a login
        return redirect()->route('login');
    }
}
