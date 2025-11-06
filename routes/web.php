<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmpleadoController;
// Importa los nuevos controladores que crearemos después
use App\Http\Controllers\TecnicoDashboardController; 
// use App\Http\Controllers\ReclamoResolucionController;
// use App\Http\Controllers\Admin\SlaPoliticaController;
// use App\Http\Controllers\Admin\CatTipoIncidenteController;
// use App\Http\Controllers\Admin\CatCausaRaizController;

// === PÁGINAS PÚBLICAS ===
Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/recursos', fn() => view('recursos'))->name('recursos');

// === AUTENTICACIÓN (Login / Registro) ===
// (Es mejor agruparlas)
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/sign_up', [RegisterController::class, 'show'])->name('register');
Route::post('/sign_up', [RegisterController::class, 'store'])->name('register.store');


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