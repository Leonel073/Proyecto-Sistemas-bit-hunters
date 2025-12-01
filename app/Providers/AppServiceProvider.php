<?php

namespace App\Providers;

use App\Models\Reclamo;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar route model binding para Reclamo usando idReclamo
        Route::bind('reclamo', function ($value) {
            return Reclamo::where('idReclamo', $value)->firstOrFail();
        });
    }
}
