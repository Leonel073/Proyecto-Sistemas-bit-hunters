<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importante
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  // El rol que pasamos desde la ruta (ej: 'SupervisorOperador')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Especificamos que queremos el guard 'empleado'
        $guard = 'empleado'; 

        // 2. Obtenemos el rol del usuario autenticado CON ESE GUARD
        // Damos por hecho que AuthEmpleado (tu Archivo 2) ya verificó el login
        $userRole = Auth::guard($guard)->user()->rol;

        // 3. Comparamos el rol del usuario con el rol que requiere la ruta
        if ($userRole !== $role) {
            
            // Si no es el rol correcto, negamos el acceso
            abort(403, 'ACCESO NO AUTORIZADO. ROL INCORRECTO.');
        }
        
        // 4. Si el rol es correcto, dejamos pasar la solicitud
        return $next($request);
    }
}