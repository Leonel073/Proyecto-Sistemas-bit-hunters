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

// === PÚBLICO ===
Route::get('/', function () { return view('index'); })->name('home');
Route::get('/recursos', fn() => view('recursos'))->name('recursos');

// === AUTENTICACIÓN ===
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth:web,empleado');

Route::get('/sign_up', [RegisterController::class, 'show'])->name('register');
Route::post('/sign_up', [RegisterController::class, 'store'])->name('register.store');

// === CLIENTES (USUARIOS) ===
Route::middleware('auth')->group(function () {
    Route::view('/formulario', 'formulario')->name('formulario');
    Route::view('/seguimiento', 'seguimiento')->name('seguimiento');
    Route::post('/reclamo', [ReclamoController::class, 'storeFront'])->name('reclamo.store');
});

// === EMPLEADOS (Panel de Administración) ===

// 1. GERENTE (ADMIN)
Route::middleware(['auth:empleado', 'role:Gerente'])->prefix('admin')->name('admin.')->group(function () {
    // Panel principal (usa el index de empleados)
    Route::resource('empleados', EmpleadoController::class); 
    // Esto crea automáticamente la ruta: admin.empleados.index

    // Rutas extra para empleados
    Route::get('empleados-eliminados', [EmpleadoController::class, 'deleted'])->name('empleados.deleted');
    Route::put('empleados/{id}/restore', [EmpleadoController::class, 'restore'])->name('empleados.restore');

    // Gestión de Usuarios
    Route::resource('usuarios', UsuarioController::class)->except(['create', 'store', 'show']);
    Route::put('usuarios/{id}/restore', [UsuarioController::class, 'restore'])->name('usuarios.restore');
});

// 2. SUPERVISOR DE OPERADORES
Route::middleware(['auth:empleado', 'role:SupervisorOperador'])
    ->prefix('supervisor/operadores')->name('supervisor.operadores.')->group(function () {
    
    Route::get('/', [SupervisorOperadorController::class, 'index'])->name('index'); // Ruta: supervisor.operadores.index
    Route::get('/create', [SupervisorOperadorController::class, 'create'])->name('create');
    Route::post('/', [SupervisorOperadorController::class, 'store'])->name('store');
    Route::get('/deleted', [SupervisorOperadorController::class, 'deleted'])->name('deleted');
    Route::put('/{id}/restore', [SupervisorOperadorController::class, 'restore'])->name('restore');
    Route::get('/{id}/edit', [SupervisorOperadorController::class, 'edit'])->name('edit');
    Route::put('/{id}', [SupervisorOperadorController::class, 'update'])->name('update');
    Route::delete('/{id}', [SupervisorOperadorController::class, 'destroy'])->name('destroy');
});

// 3. SUPERVISOR DE TÉCNICOS
Route::middleware(['auth:empleado', 'role:SupervisorTecnico'])
    ->prefix('supervisor/tecnicos')->name('supervisor.tecnicos.')->group(function () {
    
    Route::get('/', [SupervisorTecnicoController::class, 'index'])->name('index'); // Ruta: supervisor.tecnicos.index
    Route::get('/create', [SupervisorTecnicoController::class, 'create'])->name('create');
    Route::post('/', [SupervisorTecnicoController::class, 'store'])->name('store');
    Route::get('/deleted', [SupervisorTecnicoController::class, 'deleted'])->name('deleted');
    Route::put('/{id}/restore', [SupervisorTecnicoController::class, 'restore'])->name('restore');
    Route::get('/{id}/edit', [SupervisorTecnicoController::class, 'edit'])->name('edit');
    Route::put('/{id}', [SupervisorTecnicoController::class, 'update'])->name('update');
    Route::delete('/{id}', [SupervisorTecnicoController::class, 'destroy'])->name('destroy');
});

// 4. TÉCNICO
Route::middleware(['auth:empleado', 'role:Tecnico'])->prefix('tecnico')->name('tecnico.')->group(function () {
    Route::get('/dashboard', [TecnicoDashboardController::class, 'index'])->name('dashboard');
    Route::post('/estado/actualizar', [TecnicoDashboardController::class, 'actualizarEstadoDisponibilidad'])->name('estado.update');
    Route::post('/reclamo/{reclamo}/resolver', [ReclamoResolucionController::class, 'resolver'])->name('reclamo.resolver');
});

// 5. OPERADOR
Route::middleware(['auth:empleado', 'role:Operador'])->prefix('operador')->name('operador.')->group(function () {
    Route::get('/panel', [OperadorController::class, 'panel'])->name('panel');
    Route::get('/reclamos/nuevos', [OperadorController::class, 'nuevos'])->name('reclamos.nuevos');
    Route::get('/reclamos/mis', [OperadorController::class, 'mis'])->name('reclamos.mis');
    Route::get('/tecnicos', [OperadorController::class, 'tecnicos'])->name('tecnicos');
    Route::post('/reclamo/tomar/{reclamo}', [OperadorController::class, 'tomar'])->name('reclamo.tomar');
    Route::post('/reclamo/asignar-tecnico/{reclamo}', [OperadorController::class, 'asignarTecnico'])->name('reclamo.asignarTecnico');
});