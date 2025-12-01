<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RegistroAuditoria;

class AdminController extends Controller
{
    /**
     * Muestra el Panel de Control Técnico (Dashboard de Super Admin).
     */
    public function controlPanel()
    {
        // 1. Obtener los 10 logs de auditoría más recientes
        $latestLogs = RegistroAuditoria::orderBy('created_at', 'desc')->limit(10)->get();

        // 2. Obtener el estado de las tablas clave (simple conteo)
        $tableCounts = [
            'Empleados' => DB::table('empleados')->count(),
            'Usuarios (Clientes)' => DB::table('usuarios')->count(),
            'Reclamos Activos' => DB::table('reclamos')->whereIn('estado', ['Nuevo', 'Abierto', 'En Proceso'])->count(),
            'Registros de Auditoría' => DB::table('registros_auditoria')->count(),
        ];
        
        // 3. Obtener el estado del sistema (simulado)
        $systemInfo = [
            'App Env' => config('app.env'),
            'App Debug' => config('app.debug') ? 'Activado' : 'Desactivado',
        ];

        return view('admin.control', compact('latestLogs', 'tableCounts', 'systemInfo'));
    }

    /**
     * Muestra el historial de Migraciones.
     */
    public function migrations()
    {
        // Esto consulta directamente la tabla 'migrations'
        $migrations = DB::table('migrations')->orderBy('batch', 'desc')->get();
        
        return view('admin.migrations', compact('migrations'));
    }
}