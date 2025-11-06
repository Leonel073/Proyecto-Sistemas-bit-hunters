<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TecnicoDashboardController; 
use App\Http\Controllers\ReclamoResolucionController;
// use App\Http\Controllers\ReclamoResolucionController;
// use App\Http\Controllers\Admin\SlaPoliticaController;
// use App\Http\Controllers\Admin\CatTipoIncidenteController;
// use App\Http\Controllers\Admin\CatCausaRaizController;

// Página principal
Route::get('/', function () {
    return view('index');
})->name('home');

// === PÁGINAS PÚBLICAS ===
Route::get('/recursos', fn() => view('recursos'))->name('recursos');

// === RUTAS PROTEGIDAS ===
Route::middleware('auth')->group(function () {
    Route::view('/formulario', 'formulario')->name('formulario');
    Route::view('/seguimiento', 'seguimiento')->name('seguimiento');
});

// === LOGIN ===
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// === REGISTRO ===
Route::get('/sign_up', [RegisterController::class, 'show'])->name('register');
Route::post('/sign_up', [RegisterController::class, 'store'])->name('register.store');


Route::get('/usuarios', [EmpleadoController::class, 'index'])->name('usuarios');
Route::get('/admin/users/create', [EmpleadoController::class, 'create'])->name('empleados.create');
Route::post('/admin/users', [EmpleadoController::class, 'store'])->name('empleados.store');
Route::get('/admin/users/deleted', [EmpleadoController::class, 'deleted'])->name('usuarios.deleted');
Route::delete('/admin/users/{id}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');

Route::get('empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
Route::get('empleados/create', [EmpleadoController::class, 'create'])->name('empleados.create');
Route::post('empleados', [EmpleadoController::class, 'store'])->name('empleados.store');
Route::get('empleados/{id}/edit', [EmpleadoController::class, 'edit'])->name('empleados.edit');
Route::put('empleados/{id}', [EmpleadoController::class, 'update'])->name('empleados.update');
Route::delete('empleados/{id}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');

// === RUTAS DE USUARIOS (administración general) ===
Route::get('/usuarios', [EmpleadoController::class, 'index'])->name('usuarios');
Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

Route::put('empleados/{id}/restore', [EmpleadoController::class, 'restore'])->name('empleados.restore');
Route::put('usuarios/{id}/restore', [UsuarioController::class, 'restore'])->name('usuarios.restore');

// === RUTAS DE CLIENTE AUTENTICADO ===
Route::middleware('auth')->group(function () {
    Route::view('/formulario', 'formulario')->name('formulario');
    Route::view('/seguimiento', 'seguimiento')->name('seguimiento');
});


// === RUTAS DE ADMINISTRACIÓN ===
// (Aquí pondremos todo lo del panel de Admin y Gerente)
// Usamos ->prefix('admin') para que todas las URIs empiecen con /admin/...
// Usamos ->name('admin.') para que todos los nombres de ruta empiecen con admin.
Route::middleware(['auth', 'role:Gerente'])->prefix('admin')->name('admin.')->group(function () {

    // 1. CRUD DE EMPLEADOS (Esto reemplaza tus 12+ líneas)
    // Esto crea automáticamente:
    // - admin.empleados.index   (GET /admin/empleados)
    // - admin.empleados.create  (GET /admin/empleados/create)
    // - admin.empleados.store   (POST /admin/empleados)
    // - admin.empleados.show    (GET /admin/empleados/{empleado})
    // - admin.empleados.edit    (GET /admin/empleados/{empleado}/edit)
    // - admin.empleados.update  (PUT/PATCH /admin/empleados/{empleado})
    // - admin.empleados.destroy (DELETE /admin/empleados/{empleado})
    Route::resource('empleados', EmpleadoController::class);

    // 2. RUTA EXTRA PARA EMPLEADOS BORRADOS
    // (La única que 'resource' no incluye es la de 'eliminados')
    Route::get('empleados-eliminados', [EmpleadoController::class, 'deleted'])->name('empleados.deleted');


    // 3. AQUÍ AÑADIREMOS LOS CRUDS DE CATÁLOGOS (Paso Siguiente)
    // Route::resource('politicas-sla', SlaPoliticaController::class);
    // Route::resource('tipos-incidente', CatTipoIncidenteController::class);
    // Route::resource('causas-raiz', CatCausaRaizController::class);

});


// === RUTAS DE TÉCNICO ===
// ¡BLOQUE ACTIVADO! (Le quitamos los //)
// Usamos el rol 'Tecnico' (asegúrate que tu middleware se llame así)
Route::middleware(['auth', 'role:Tecnico'])->prefix('tecnico')->name('tecnico.')->group(function () {
    // Ver el panel
    Route::get('/dashboard', [TecnicoDashboardController::class, 'index'])->name('dashboard');
    
    // Actualizar el estado del TÉCNICO (En Ruta, Ocupado, etc.)
    Route::post('/estado/actualizar', [TecnicoDashboardController::class, 'actualizarEstadoDisponibilidad'])->name('estado.update');
    
    // Resolver un RECLAMO (registrar solución y marcar como Resuelto)
    Route::post('/reclamo/{reclamo}/resolver', [ReclamoResolucionController::class, 'resolver'])->name('reclamo.resolver');
});