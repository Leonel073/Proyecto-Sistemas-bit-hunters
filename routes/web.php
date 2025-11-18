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

/*
|--------------------------------------------------------------------------
| Rutas Web
|--------------------------------------------------------------------------
*/

// === PÁGINAS PÚBLICAS ===
Route::get('/', function () { return view('index'); })->name('home');
Route::get('/recursos', fn() => view('recursos'))->name('recursos');

// === AUTENTICACIÓN (PÚBLICO) ===
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/sign_up', [RegisterController::class, 'show'])->name('register');
Route::post('/sign_up', [RegisterController::class, 'store'])->name('register.store');

// Logout (Debe estar protegido, usualmente para usuarios logueados)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth:web,empleado');


// === RUTAS DE CLIENTE (USUARIO) AUTENTICADO ===
Route::middleware('auth')->group(function () { // 'auth' por defecto es 'auth:web'
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
    Route::put('/empleados/{id}/restore', [EmpleadoController::class, 'restore'])->name('empleados.restore'); // Nota: 'restore' está en EmpleadoController
    
    // Gestión de Usuarios (Clientes)
    Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    Route::put('/usuarios/{id}/restore', [UsuarioController::class, 'restore'])->name('usuarios.restore'); // Nota: 'restore' está en UsuarioController
    
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

// --- TÉCNICO ---
Route::middleware(['auth:empleado', 'role:Tecnico'])->prefix('tecnico')->name('tecnico.')->group(function () {
    Route::get('/dashboard', [TecnicoDashboardController::class, 'index'])->name('dashboard');
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