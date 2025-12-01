<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\OperadorController;
use App\Http\Controllers\ReclamoController;
use App\Http\Controllers\SupervisorOperadorController;
use App\Http\Controllers\SupervisorTecnicoController;
use App\Http\Controllers\TecnicoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AdminController; // <--- Importación del nuevo controlador de Admin
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Added for debug route

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS Y CLIENTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('public.index');
})->name('home');
Route::get('/recursos', fn () => view('public.recursos'))->name('recursos');

// === AUTENTICACIÓN ===
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); 

Route::get('/sign_up', [RegisterController::class, 'show'])->name('register');
Route::post('/sign_up', [RegisterController::class, 'store'])->name('register.store');

// RUTAS DE CLIENTES
// RUTAS DE CLIENTES (Y ACCESO ADMIN)
Route::middleware(['auth:web,empleado'])->group(function () {
    Route::get('/formulario', [ReclamoController::class, 'create'])->name('formulario');
    Route::get('/seguimiento', [ReclamoController::class, 'seguimiento'])->name('seguimiento');
    Route::post('/reclamo', [ReclamoController::class, 'store'])->name('reclamo.store');
    
    // Perfil solo para clientes (web)
    Route::middleware('auth:web')->group(function() {
        Route::get('/perfil/editar', [UsuarioController::class, 'perfil'])->name('perfil.editar');
        Route::put('/perfil/editar', [UsuarioController::class, 'actualizarPerfil'])->name('perfil.update');
        
        // Notificaciones
        Route::get('/notificaciones', [\App\Http\Controllers\NotificacionController::class, 'indexView'])->name('notificaciones.index');
        Route::post('/notificaciones/marcar-todas', [\App\Http\Controllers\NotificacionController::class, 'marcarTodas'])->name('notificaciones.marcarTodas');

        // Acciones de Reclamo (Cliente)
        Route::put('/reclamo/{reclamo}/cerrar', [ReclamoController::class, 'cerrar'])->name('reclamo.cerrar');
        Route::put('/reclamo/{reclamo}/reabrir', [ReclamoController::class, 'reabrir'])->name('reclamo.reabrir');
    });
});

/*
|--------------------------------------------------------------------------
| RUTAS DE EMPLEADOS (JERARQUÍA CON INCLUSIÓN DE SUPER ADMIN)
|--------------------------------------------------------------------------
*/

// 0. ÁREA DE SUPER ADMINISTRACIÓN (ROL EXCLUSIVO)
// Acceso exclusivo: Solo SuperAdmin
Route::middleware(['auth:empleado', 'role:SuperAdmin'])
    ->prefix('admin')->name('admin.')->group(function () {

        // Dashboard de Control Técnico (lo que creamos en el paso anterior)
        Route::get('control', [AdminController::class, 'controlPanel'])->name('control');
        // Acceso a Registros Internos y Tablas de Configuración
        Route::get('migrations', [AdminController::class, 'migrations'])->name('migrations');
        // Herramientas de sistema exclusivas
    });


// 1. ÁREA DE GERENCIA (GERENTE DE SOPORTE)
// Acceso: Gerente Y SuperAdmin
Route::middleware(['auth:empleado', 'role:Gerente,SuperAdmin']) // <--- ACTUALIZADO
    ->prefix('gerente')->name('gerente.')->group(function () {

        // Dashboard Principal
        Route::get('dashboard', [\App\Http\Controllers\GerenteDashboardController::class, 'index'])->name('dashboard');

        // CRUD Empleados
        Route::resource('empleados', EmpleadoController::class);
        Route::get('empleados-eliminados', [EmpleadoController::class, 'deleted'])->name('empleados.deleted');
        Route::put('empleados/{id}/restore', [EmpleadoController::class, 'restore'])->name('empleados.restore');

        // CRUD Usuarios
        Route::resource('usuarios', UsuarioController::class)->except(['create', 'store', 'show']);
        Route::get('usuarios', [\App\Http\Controllers\UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('usuarios-eliminados', [\App\Http\Controllers\UsuarioController::class, 'deleted'])->name('usuarios.deleted');
        Route::put('usuarios/{id}/restore', [UsuarioController::class, 'restore'])->name('usuarios.restore');
        Route::put('usuarios/{id}/toggle-block', [UsuarioController::class, 'toggleBlock'])->name('usuarios.toggle-block'); // <--- NUEVA RUTA BLOQUEO

        // Auditoría
        Route::get('auditoria', [\App\Http\Controllers\RegistroAuditoriaController::class, 'index'])->name('auditoria');

        // SLA Políticas (HU-12)
        Route::get('sla-politicas', [\App\Http\Controllers\SlaPoliticaController::class, 'index'])->name('sla-politicas');
        Route::post('sla-politicas', [\App\Http\Controllers\SlaPoliticaController::class, 'store'])->name('sla-politicas.store');
        Route::put('sla-politicas/{id}', [\App\Http\Controllers\SlaPoliticaController::class, 'update'])->name('sla-politicas.update');
        Route::delete('sla-politicas/{id}', [\App\Http\Controllers\SlaPoliticaController::class, 'destroy'])->name('sla-politicas.destroy');

        // Zonas/Comunidades (HU-6)
        Route::get('zonas', [\App\Http\Controllers\ZonaController::class, 'index'])->name('zonas');
        Route::post('zonas', [\App\Http\Controllers\ZonaController::class, 'store'])->name('zonas.store');
        Route::put('zonas/{id}', [\App\Http\Controllers\ZonaController::class, 'update'])->name('zonas.update');
        Route::delete('zonas/{id}', [\App\Http\Controllers\ZonaController::class, 'destroy'])->name('zonas.destroy');
    });

// 2. ÁREA DE SUPERVISIÓN OPERADORES
// Acceso: SupervisorOperador, Gerente Y SuperAdmin
Route::middleware(['auth:empleado', 'role:SupervisorOperador,Gerente,SuperAdmin']) // <--- ACTUALIZADO
    ->prefix('supervisor/operadores')->name('supervisor.operadores.')->group(function () {

        // Gestión de Operadores (CRUD)
        Route::get('/', [SupervisorOperadorController::class, 'index'])->name('index');
        Route::get('/crear', [SupervisorOperadorController::class, 'create'])->name('create');
        Route::post('/', [SupervisorOperadorController::class, 'store'])->name('store');
        Route::get('/{id}/editar', [SupervisorOperadorController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SupervisorOperadorController::class, 'update'])->name('update');
        Route::delete('/{id}', [SupervisorOperadorController::class, 'destroy'])->name('destroy');
        
        // Operadores Eliminados
        Route::get('/eliminados', [SupervisorOperadorController::class, 'deleted'])->name('deleted');
        Route::put('/{id}/restaurar', [SupervisorOperadorController::class, 'restore'])->name('restore');

        // Reasignación
        Route::put('/reclamo/{reclamo}/reasignar', [SupervisorOperadorController::class, 'reasignarOperador'])->name('reasignar');

        // Panel del Supervisor (Dashboard y Mapa)
        Route::get('/dashboard', [SupervisorOperadorController::class, 'dashboard'])->name('dashboard');
        Route::get('/mapa', [SupervisorTecnicoController::class, 'mapa'])->name('mapa');
    });

// 3. ÁREA DE SUPERVISIÓN TÉCNICOS
// Acceso: SupervisorTecnico, Gerente Y SuperAdmin
Route::middleware(['auth:empleado', 'role:SupervisorTecnico,Gerente,SuperAdmin']) // <--- ACTUALIZADO
    ->prefix('supervisor/tecnicos')->name('supervisor.tecnicos.')->group(function () {

        // Gestión de Técnicos (CRUD)
        Route::get('/', [SupervisorTecnicoController::class, 'index'])->name('index');
        Route::get('/crear', [SupervisorTecnicoController::class, 'create'])->name('create');
        Route::post('/', [SupervisorTecnicoController::class, 'store'])->name('store');
        Route::get('/{id}/editar', [SupervisorTecnicoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SupervisorTecnicoController::class, 'update'])->name('update');
        Route::delete('/{id}', [SupervisorTecnicoController::class, 'destroy'])->name('destroy');

        // Técnicos Eliminados
        Route::get('/eliminados', [SupervisorTecnicoController::class, 'deleted'])->name('deleted');
        Route::put('/{id}/restaurar', [SupervisorTecnicoController::class, 'restore'])->name('restore');

        // Reasignación
        Route::put('/reclamo/{reclamo}/reasignar', [SupervisorTecnicoController::class, 'reasignarTecnico'])->name('reasignar');

        // Panel del Supervisor (Dashboard y Mapa)
        Route::get('/dashboard', [SupervisorTecnicoController::class, 'dashboard'])->name('dashboard');
        Route::get('/mapa', [SupervisorTecnicoController::class, 'mapa'])->name('mapa');
    });

// 4. ÁREA OPERATIVA (PANEL DE OPERADOR)
// Acceso: Operador, SupervisorOperador, Gerente Y SuperAdmin
Route::middleware(['auth:empleado', 'role:Operador,SupervisorOperador,Gerente,SuperAdmin']) // <--- ACTUALIZADO
    ->prefix('operador')->name('operador.')->group(function () {

        Route::get('/panel', [OperadorController::class, 'panel'])->name('panel');
        // API endpoints internos para el panel
        Route::post('/reclamo/tomar/{reclamo}', [OperadorController::class, 'tomar'])->name('reclamo.tomar');
        Route::post('/reclamo/asignar-tecnico/{reclamo}', [OperadorController::class, 'asignarTecnico'])->name('reclamo.asignarTecnico');
    });

// 5. ÁREA TÉCNICA (DASHBOARD TÉCNICO)
// Acceso: Tecnico, SupervisorTecnico, Gerente Y SuperAdmin
Route::middleware(['auth:empleado', 'role:Tecnico,SupervisorTecnico,Gerente,SuperAdmin']) // <--- ACTUALIZADO
    ->prefix('tecnico')->name('tecnico.')->group(function () {

        // Dashboard del técnico
        Route::get('/dashboard', [TecnicoController::class, 'panel'])->name('dashboard');

        // Workflow
        Route::put('/estado', [TecnicoController::class, 'actualizarEstado'])->name('actualizar.estado');
        Route::put('/reclamos/{reclamo}/aceptar', [TecnicoController::class, 'aceptarReclamo'])->name('reclamos.aceptar');
        Route::put('/reclamos/{reclamo}/resolver', [TecnicoController::class, 'resolverReclamo'])->name('reclamos.resolver');
    });

// DEBUG ROUTE
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