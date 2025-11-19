<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  // El rol requerido (ej: 'Gerente')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Definir el guard de empleados
        $guard = 'empleado';

        // 2. Seguridad: Verificar si el usuario está realmente logueado como empleado
        if (!Auth::guard($guard)->check()) {
            // Si no está logueado, lo mandamos al login o lanzamos error.
            // Normalmente el middleware 'auth:empleado' ya maneja esto, 
            // pero es bueno tener doble seguridad.
            return redirect()->route('login')->withErrors(['email' => 'Debes iniciar sesión para acceder.']);
        }

        // 3. Obtener el rol del usuario actual
        $userRole = Auth::guard($guard)->user()->rol;

        // 4. Comparar roles
        // Si el rol NO es el correcto...
        if ($userRole !== $role) {
            // ... ¡Lanzamos el error 403! 
            // Laravel buscará automáticamente la vista en resources/views/errors/403.blade.php
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }
        
        // 5. Si todo está bien, pase usted
        return $next($request);
    }
}