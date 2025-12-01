<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware; // ✅ Importante para verificar sesión
use Illuminate\Http\Request;
// Importamos tu middleware de Roles
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Alias para los roles
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        // ✅ LÓGICA DE REDIRECCIÓN PERSONALIZADA
        $middleware->redirectGuestsTo(function (Request $request) {

            // Rutas protegidas de empleados
            $esRutaProtegida = $request->is('admin/*') ||
                               $request->is('supervisor/*') ||
                               $request->is('operador/*') ||
                               $request->is('tecnico/*');

            if ($esRutaProtegida) {
                // 1. Si es un CLIENTE logueado intentando entrar a zona de empleados -> ERROR 403
                if (Auth::guard('web')->check()) {
                    abort(403, 'Acceso denegado: Tu cuenta de Cliente no tiene permisos para acceder al panel interno.');
                }

                // 2. Si es un EMPLEADO logueado pero se le acabó la sesión -> LOGIN
                // (Esto permite re-loguearse)
                return route('login');
            }

            // Por defecto a login
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
