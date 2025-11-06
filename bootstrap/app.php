<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// ↓ ↓ ↓ AÑADE ESTA LÍNEA AQUÍ ↓ ↓ ↓
use App\Http\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // ↓ ↓ ↓ Y AÑADE ESTA LÍNEA AQUÍ DENTRO ↓ ↓ ↓
        $middleware->alias([
            'role' => RoleMiddleware::class
        ]);

        // (Es posible que tengas otras cosas aquí, déjalas)

    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ...
    })->create();