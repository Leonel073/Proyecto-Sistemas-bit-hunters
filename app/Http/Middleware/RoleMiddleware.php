<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Necesitamos importar Auth
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  // <-- Este es el parámetro 'Gerente' que pasamos
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Comparamos el rol de la sesión (que guardamos en LoginController)
        // con el rol que requiere la ruta (ej. 'Gerente')
        if (!Auth::check() || session('user_role') !== $role) {
            
            // Si no es, le negamos el acceso...
            abort(403, 'ACCESO NO AUTORIZADO.');
        }
        
        return $next($request);
    }
}