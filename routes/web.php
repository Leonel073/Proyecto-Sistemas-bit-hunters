<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TecnicoController; // Usamos TecnicoController para el panel técnico
use App\Http\Controllers\ReclamoController; 
use App\Http\Controllers\OperadorController;
use App\Http\Controllers\SupervisorOperadorController;
use App\Http\Controllers\SupervisorTecnicoController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/
Route::get('/', function () { return view('index'); })->name('home');
Route::get('/recursos', fn() => view('recursos'))->name('recursos');

// === AUTENTICACIÓN ===
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); // Logout general

Route::get('/sign_up', [RegisterController::class, 'show'])->name('register');
Route::post('/sign_up', [RegisterController::class, 'store'])->name('register.store');

/*
|--------------------------------------------------------------------------
| RUTAS DE CLIENTES (USUARIOS)
|--------------------------------------------------------------------------
| Solo para usuarios normales (Guard: web)
*/
Route::middleware('auth')->group(function () {
    Route::view('/formulario', 'formulario')->name('formulario');
    Route::view('/seguimiento', 'seguimiento')->name('seguimiento');
    
    // CORRECCIÓN 1: Cambiamos 'storeFront' a 'store' para solucionar el error de método indefinido.
    Route::post('/reclamo', [ReclamoController::class, 'store'])->name('reclamo.store');

    // Editar Perfil de Usuario
    Route::get('/perfil/editar', [UsuarioController::class, 'perfil'])->name('perfil.editar');
    Route::put('/perfil/editar', [UsuarioController::class, 'actualizarPerfil'])->name('perfil.update');
});

/*
|--------------------------------------------------------------------------
| RUTAS DE EMPLEADOS (JERARQUÍA)
|--------------------------------------------------------------------------
*/

// 1. ÁREA DE GERENCIA (ADMINISTRADOR)
// Acceso exclusivo: Solo Gerente
Route::middleware(['auth:empleado', 'role:Gerente'])
    ->prefix('admin')->name('admin.')->group(function () {
    
    // CRUD Empleados
    Route::resource('empleados', EmpleadoController::class);
    Route::get('empleados-eliminados', [EmpleadoController::class, 'deleted'])->name('empleados.deleted');
    Route::put('empleados/{id}/restore', [EmpleadoController::class, 'restore'])->name('empleados.restore');

    // CRUD Usuarios
    Route::resource('usuarios', UsuarioController::class)->except(['create', 'store', 'show']);
    Route::put('usuarios/{id}/restore', [UsuarioController::class, 'restore'])->name('usuarios.restore');
});

// 2. ÁREA DE SUPERVISIÓN OPERADORES
// Acceso: SupervisorOperador y Gerente
Route::middleware(['auth:empleado', 'role:SupervisorOperador,Gerente'])
    ->prefix('supervisor/operadores')->name('supervisor.operadores.')->group(function () {
    
    // Gestión de Operadores
    Route::get('/', [SupervisorOperadorController::class, 'index'])->name('index'); 
    Route::get('/create', [SupervisorOperadorController::class, 'create'])->name('create');
    Route::post('/', [SupervisorOperadorController::class, 'store'])->name('store');
    Route::get('/deleted', [SupervisorOperadorController::class, 'deleted'])->name('deleted');
    Route::put('/{id}/restore', [SupervisorOperadorController::class, 'restore'])->name('restore');
    Route::get('/{id}/edit', [SupervisorOperadorController::class, 'edit'])->name('edit');
    Route::put('/{id}', [SupervisorOperadorController::class, 'update'])->name('update');
    Route::delete('/{id}', [SupervisorOperadorController::class, 'destroy'])->name('destroy');

    // Panel del Supervisor (Dashboard específico si existe)
    Route::get('/panel', [SupervisorOperadorController::class, 'dashboard'])->name('dashboard');
    Route::put('/reclamo/{reclamo}/reasignar', [SupervisorOperadorController::class, 'reasignarOperador'])->name('reasignar');
});

// 3. ÁREA DE SUPERVISIÓN TÉCNICOS
// Acceso: SupervisorTecnico y Gerente
Route::middleware(['auth:empleado', 'role:SupervisorTecnico,Gerente'])
    ->prefix('supervisor/tecnicos')->name('supervisor.tecnicos.')->group(function () {
    
    // Gestión de Técnicos
    Route::get('/', [SupervisorTecnicoController::class, 'index'])->name('index');
    Route::get('/create', [SupervisorTecnicoController::class, 'create'])->name('create');
    Route::post('/', [SupervisorTecnicoController::class, 'store'])->name('store');
    Route::get('/deleted', [SupervisorTecnicoController::class, 'deleted'])->name('deleted');
    Route::put('/{id}/restore', [SupervisorTecnicoController::class, 'restore'])->name('restore');
    Route::get('/{id}/edit', [SupervisorTecnicoController::class, 'edit'])->name('edit');
    Route::put('/{id}', [SupervisorTecnicoController::class, 'update'])->name('update');
    Route::delete('/{id}', [SupervisorTecnicoController::class, 'destroy'])->name('destroy');
});

// 4. ÁREA OPERATIVA (PANEL DE OPERADOR)
// Acceso: Operador, SupervisorOperador y Gerente
Route::middleware(['auth:empleado', 'role:Operador,SupervisorOperador,Gerente'])
    ->prefix('operador')->name('operador.')->group(function () {
    
    Route::get('/panel', [OperadorController::class, 'panel'])->name('panel');
    // API endpoints internos para el panel
    Route::get('/reclamos/nuevos', [OperadorController::class, 'nuevos'])->name('reclamos.nuevos');
    Route::get('/reclamos/mis', [OperadorController::class, 'mis'])->name('reclamos.mis');
    Route::get('/tecnicos', [OperadorController::class, 'tecnicos'])->name('tecnicos');
    
    // Solo el Operador debería ejecutar estas acciones (aunque el supervisor tenga acceso a ver)
    Route::post('/reclamo/tomar/{reclamo}', [OperadorController::class, 'tomar'])->name('reclamo.tomar');
    Route::post('/reclamo/asignar-tecnico/{reclamo}', [OperadorController::class, 'asignarTecnico'])->name('reclamo.asignarTecnico');
});

/// 5. ÁREA TÉCNICA (DASHBOARD TÉCNICO)
// Acceso: Tecnico, SupervisorTecnico y Gerente
Route::middleware(['auth:empleado', 'role:Tecnico,SupervisorTecnico,Gerente'])
    ->prefix('tecnico')->name('tecnico.')->group(function () {
    
    Route::get('/dashboard', [TecnicoDashboardController::class, 'index'])->name('dashboard');
    Route::post('/estado/actualizar', [TecnicoDashboardController::class, 'actualizarEstadoDisponibilidad'])->name('estado.update');
    Route::post('/reclamo/{reclamo}/resolver', [ReclamoResolucionController::class, 'resolver'])->name('reclamo.resolver');
});