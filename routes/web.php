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
use App\Http\Controllers\SupervisorTecnicoController;

// === PÁGINAS PÚBLICAS ===
Route::get('/', function () { return view('index'); })->name('home');
Route::get('/recursos', fn() => view('recursos'))->name('recursos');

// === AUTENTICACIÓN (PÚBLICO) ===
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Recursos públicos
Route::get('/recursos', fn() => view('recursos'))->name('recursos');

// --- LOGIN (Para Clientes y Empleados) ---
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
// El logout se maneja al final con el middleware
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); 

// --- REGISTRO (Solo Clientes) ---
Route::get('/sign_up', [RegisterController::class, 'show'])->name('register');
Route::post('/sign_up', [RegisterController::class, 'store'])->name('register.store');

// Logout (Debe estar protegido, usualmente para usuarios logueados)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth:web,empleado');


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

// === RUTAS DE EMPLEADOS (Protegidas por 'auth:empleado') ===

// --- GERENTE / ADMIN ---
Route::middleware(['auth:empleado', 'role:Gerente'])->prefix('admin')->group(function () {
    
    // Panel principal del Admin
    Route::get('/usuarios', [EmpleadoController::class, 'index'])->name('usuarios');
    
    // Gestión de Empleados (CRUD Completo)
    Route::get('/empleados/create', [EmpleadoController::class, 'create'])->name('empleados.create');
    Route::post('/empleados', [EmpleadoController::class, 'store'])->name('empleados.store');
    Route::get('/empleados/{id}/edit', [EmpleadoController::class, 'edit'])->name('empleados.edit');
    Route::put('/empleados/{id}', [EmpleadoController::class, 'update'])->name('empleados.update');
    Route::delete('/empleados/{id}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');
    Route::put('/empleados/{id}/restore', [EmpleadoController::class, 'restore'])->name('empleados.restore');
    
    // Gestión de Usuarios (Clientes)
    Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    Route::put('/usuarios/{id}/restore', [UsuarioController::class, 'restore'])->name('usuarios.restore'); 
    
    // Ver todos los eliminados
    Route::get('/usuarios-eliminados', [EmpleadoController::class, 'deleted'])->name('usuarios.deleted');
});

// --- SUPERVISOR DE OPERADORES ---
Route::middleware(['auth:empleado', 'role:SupervisorOperador'])
    ->prefix('supervisor/operadores')->name('supervisor.operadores.')->group(function () {
    
    Route::get('/', [SupervisorOperadorController::class, 'index'])->name('index');
    Route::get('/create', [SupervisorOperadorController::class, 'create'])->name('create');
    Route::post('/', [SupervisorOperadorController::class, 'store'])->name('store');
    Route::get('/deleted', [SupervisorOperadorController::class, 'deleted'])->name('deleted');
    Route::put('/{id}/restore', [SupervisorOperadorController::class, 'restore'])->name('restore');
    
    // Rutas de Edición y Borrado
    Route::get('/{id}/edit', [SupervisorOperadorController::class, 'edit'])->name('edit');
    Route::put('/{id}', [SupervisorOperadorController::class, 'update'])->name('update');
    Route::delete('/{id}', [SupervisorOperadorController::class, 'destroy'])->name('destroy');
});

// --- SUPERVISOR DE TÉCNICOS ---
Route::middleware(['auth:empleado', 'role:SupervisorTecnico'])
    ->prefix('supervisor/tecnicos')->name('supervisor.tecnicos.')->group(function () {
    
    Route::get('/', [SupervisorTecnicoController::class, 'index'])->name('index');
    Route::get('/create', [SupervisorTecnicoController::class, 'create'])->name('create');
    Route::post('/', [SupervisorTecnicoController::class, 'store'])->name('store');
    Route::get('/deleted', [SupervisorTecnicoController::class, 'deleted'])->name('deleted');
    Route::put('/{id}/restore', [SupervisorTecnicoController::class, 'restore'])->name('restore');
    
    // Rutas de Edición y Borrado
    Route::get('/{id}/edit', [SupervisorTecnicoController::class, 'edit'])->name('edit');
    Route::put('/{id}', [SupervisorTecnicoController::class, 'update'])->name('update');
    Route::delete('/{id}', [SupervisorTecnicoController::class, 'destroy'])->name('destroy');
});

// --- TÉCNICO (GRUPO OFICIAL) ---
// La ruta que debe resolver el controlador de login es tecnico.dashboard
Route::middleware(['auth:empleado', 'role:Tecnico'])->prefix('tecnico')->name('tecnico.')->group(function () {
    Route::get('/dashboard', [TecnicoDashboardController::class, 'index'])->name('dashboard'); // Name: tecnico.dashboard
    Route::post('/estado/actualizar', [TecnicoDashboardController::class, 'actualizarEstadoDisponibilidad'])->name('estado.update');
    Route::post('/reclamo/{reclamo}/resolver', [ReclamoResolucionController::class, 'resolver'])->name('reclamo.resolver');
});

// --- OPERADOR ---
Route::middleware(['auth:empleado', 'role:Operador'])->prefix('operador')->name('operador.')->group(function () {
    Route::get('/panel', [OperadorController::class, 'panel'])->name('panel');
    Route::get('/reclamos/nuevos', [OperadorController::class, 'nuevos'])->name('reclamos.nuevos');
    Route::get('/reclamos/mis', [OperadorController::class, 'mis'])->name('reclamos.mis');
    Route::get('/tecnicos', [OperadorController::class, 'tecnicos'])->name('tecnicos');
    Route::post('/reclamo/tomar/{reclamo}', [OperadorController::class, 'tomar'])->name('reclamo.tomar');
    Route::post('/reclamo/asignar-tecnico/{reclamo}', [OperadorController::class, 'asignarTecnico'])->name('reclamo.asignarTecnico');
});

/*
|--------------------------------------------------------------------------
| RUTAS DE EMPLEADOS (Panel de Administración)
|--------------------------------------------------------------------------
| Este grupo anidado usa el middleware 'auth_empleado' y luego 'role'.
| Lo limpiamos para que solo contenga rutas no duplicadas.
*/
Route::middleware(['auth_empleado'])->group(function () {

    // === RUTAS DE GERENTE ===
    // (CORREGIDO: Eliminado el prefijo /admin duplicado)
    Route::middleware(['role:Gerente'])->group(function () {
        
        // CRUD DE EMPLEADOS
        // Estas rutas están duplicadas arriba, las puedes dejar o eliminar si están bien definidas arriba.
        // Las dejo comentadas para evitar conflicto de nombres.
        /*
        Route::resource('empleados', EmpleadoController::class);
        Route::get('empleados-eliminados', [EmpleadoController::class, 'deleted'])->name('empleados.deleted');
        Route::put('empleados/{id}/restore', [EmpleadoController::class, 'restore'])->name('empleados.restore');
        */

        // CRUD DE CLIENTES (USUARIO)
        // Estas rutas también están duplicadas en el grupo 'admin' superior.
        /*
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
        Route::put('usuarios/{id}/restore', [UsuarioController::class, 'restore'])->name('usuarios.restore');
        */
    });

    // === RUTAS DE SUPERVISOR DE OPERADORES ===
    // Este bloque está bien, no hay duplicación con el bloque superior.
    Route::middleware(['role:SupervisorOperador'])->group(function () {
        Route::get('/supervisor/operadores/panel', [SupervisorOperadorController::class, 'dashboard'])
             ->name('supervisor.operadores.dashboard');

        Route::put('/supervisor/reclamos/{reclamo}/reasignar-operador', [SupervisorOperadorController::class, 'reasignarOperador'])
             ->name('supervisor.reclamos.reasignar');
    });

    // === RUTAS DE OPERADOR ===
    // (Este grupo está duplicado y puede eliminarse)
    /*
    Route::middleware(['role:Operador'])->prefix('operador')->name('operador.')->group(function () {
        Route::get('/panel', [OperadorController::class, 'panel'])->name('panel');
        Route::get('/reclamos/nuevos', [OperadorController::class, 'nuevos'])->name('reclamos.nuevos');
        // ... (resto de rutas del operador)
    });
    */

    // === RUTAS DE TÉCNICO (BLOQUE ELIMINADO POR DUPLICACIÓN) ===
    // Este bloque CERRADO fue el que causó el conflicto.
    /* Route::middleware(['role:Tecnico'])->prefix('tecnico')->name('tecnico.')->group(function () {
        Route::get('/dashboard', [TecnicoDashboardController::class, 'index'])->name('tecnico.dashboard');
        Route::post('/estado/actualizar', [TecnicoDashboardController::class, 'actualizarEstadoDisponibilidad'])->name('tecnico.estado.update');
        Route::post('/reclamo/{reclamo}/resolver', [ReclamoResolucionController::class, 'resolver'])->name('tecnico.reclamo.resolver');
    });
    */
});