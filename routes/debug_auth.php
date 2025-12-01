<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/debug-auth', function () {
    return [
        'web_check' => Auth::guard('web')->check(),
        'web_user' => Auth::guard('web')->user(),
        'empleado_check' => Auth::guard('empleado')->check(),
        'empleado_user' => Auth::guard('empleado')->user(),
        'default_guard' => config('auth.defaults.guard'),
        'session_id' => session()->getId(),
    ];
});
