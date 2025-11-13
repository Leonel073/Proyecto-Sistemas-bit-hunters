<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TecnicoDashboardController; 
use App\Http\Controllers\ReclamoResolucionController;
use App\Http\Controllers\ReclamoController;
use App\Http\Controllers\OperadorController;
use App\Http\Controllers\SupervisorOperadorController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS Y AUTENTICACIÓN
|--------------------------------------------------------------------------
| Rutas que cualquiera puede ver (home, login, register)
*/

// Página principal
Route::get('/', function () {
    return view('index');
})->name('home');

// Recursos públicos
Route::get('/recursos', fn() => view('recursos'))->name('recursos');

// --- LOGIN (Para Clientes y Empleados) ---
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// --- REGISTRO (Solo Clientes) ---
Route::get('/sign_up', [RegisterController::class, 'show'])->name('register');
Route::post('/sign_up', [RegisterController::class, 'store'])->name('register.store');


/*
|--------------------------------------------------------------------------
| RUTAS DE CLIENTES (USUARIO)
|--------------------------------------------------------------------------
| Requieren el guard 'web' (el default 'auth')
*/
Route::middleware('auth')->group(function () {
    Route::view('/formulario', 'formulario')->name('formulario');
    Route::view('/seguimiento', 'seguimiento')->name('seguimiento');
    Route::post('/reclamo', [ReclamoController::class, 'storeFront'])->name('reclamo.store');
});


/*
|--------------------------------------------------------------------------
| RUTAS DE EMPLEADOS (Panel de Administración)
|--------------------------------------------------------------------------
| Requieren 'auth_empleado' y un ROL específico
*/
Route::middleware(['auth_empleado'])->group(function () {

    // === RUTAS DE GERENTE ===
    // (CORREGIDO: Usamos 'auth_empleado' y 'role:Gerente')
    Route::middleware(['role:Gerente'])->prefix('admin')->name('admin.')->group(function () {
        
        // CRUD DE EMPLEADOS
        Route::resource('empleados', EmpleadoController::class);
        Route::get('empleados-eliminados', [EmpleadoController::class, 'deleted'])->name('empleados.deleted');
        Route::put('empleados/{id}/restore', [EmpleadoController::class, 'restore'])->name('empleados.restore');

        // CRUD DE CLIENTES (USUARIO)
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
        Route::put('usuarios/{id}/restore', [UsuarioController::class, 'restore'])->name('usuarios.restore');

        // (Aquí irían los otros CRUDS de catálogos: SLA, CausaRaiz, etc.)
    });

    // === RUTAS DE SUPERVISOR DE OPERADORES ===
    // (Este es tu bloque, está PERFECTO)
    Route::middleware(['role:SupervisorOperador'])->group(function () {
        Route::get('/supervisor/operadores/panel', [SupervisorOperadorController::class, 'dashboard'])
             ->name('supervisor.operadores.dashboard');

        Route::put('/supervisor/reclamos/{reclamo}/reasignar-operador', [SupervisorOperadorController::class, 'reasignarOperador'])
             ->name('supervisor.reclamos.reasignar');
    });

    // === RUTAS DE OPERADOR ===
    // (CORREGIDO: Añadimos 'role:Operador')
    Route::middleware(['role:Operador'])->prefix('operador')->name('operador.')->group(function () {
        Route::get('/panel', [OperadorController::class, 'panel'])->name('panel');
        Route::get('/reclamos/nuevos', [OperadorController::class, 'nuevos'])->name('reclamos.nuevos');
        Route::get('/reclamos/mis', [OperadorController::class, 'mis'])->name('reclamos.mis');
        Route::get('/tecnicos', [OperadorController::class, 'tecnicos'])->name('tecnicos');
        Route::post('/reclamo/tomar/{reclamo}', [OperadorController::class, 'tomar'])->name('reclamo.tomar');
        Route::post('/reclamo/asignar-tecnico/{reclamo}', [OperadorController::class, 'asignarTecnico'])->name('reclamo.asignarTecnico');
    });

    // === RUTAS DE TÉCNICO ===
    // (CORREGIDO: Usamos 'auth_empleado' y 'role:Tecnico')
    Route::middleware(['role:Tecnico'])->prefix('tecnico')->name('tecnico.')->group(function () {
        Route::get('/dashboard', [TecnicoDashboardController::class, 'index'])->name('tecnico.dashboard');
        Route::post('/estado/actualizar', [TecnicoDashboardController::class, 'actualizarEstadoDisponibilidad'])->name('tecnico.estado.update');
        Route::post('/reclamo/{reclamo}/resolver', [ReclamoResolucionController::class, 'resolver'])->name('tecnico.reclamo.resolver');
    });

});

