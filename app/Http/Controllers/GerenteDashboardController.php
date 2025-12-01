<?php

namespace App\Http\Controllers;

use App\Models\Reclamo;
use App\Models\Empleado;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GerenteDashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();
        
        // Estadísticas principales
        $totalReclamos = Reclamo::count();
        $reclamosHoy = Reclamo::whereDate('fechaCreacion', $hoy)->count();
        $reclamosResueltosHoy = Reclamo::whereDate('fechaResolucion', $hoy)
            ->whereIn('estado', ['Resuelto', 'Cerrado'])
            ->count();
        $reclamosPendientes = Reclamo::whereNotIn('estado', ['Resuelto', 'Cerrado'])->count();
        
        // Por estado
        $porEstado = Reclamo::selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->get()
            ->pluck('total', 'estado');
        
        // Por prioridad
        $porPrioridad = Reclamo::selectRaw('prioridad, COUNT(*) as total')
            ->groupBy('prioridad')
            ->get()
            ->pluck('total', 'prioridad');
        
        // Reclamos recientes (últimos 10)
        $reclamosRecientes = Reclamo::with(['usuario', 'tecnico', 'operador'])
            ->orderBy('fechaCreacion', 'desc')
            ->limit(10)
            ->get();
        
        // Estadísticas de personal
        $totalEmpleados = Empleado::where('estado', 'Activo')->count();
        $totalUsuarios = Usuario::where('estado', 'Activo')->count();
        
        // Tiempo promedio de resolución (últimos 30 días)
        $reclamosResueltos = Reclamo::whereNotNull('fechaResolucion')
            ->where('fechaResolucion', '>=', Carbon::now()->subDays(30))
            ->get();
        
        $tiempoPromedio = 0;
        if ($reclamosResueltos->count() > 0) {
            $totalHoras = $reclamosResueltos->sum(function($reclamo) {
                return Carbon::parse($reclamo->fechaCreacion)
                    ->diffInHours(Carbon::parse($reclamo->fechaResolucion));
            });
            $tiempoPromedio = round($totalHoras / $reclamosResueltos->count(), 1);
        }
        
        return view('gerente.dashboard', compact(
            'totalReclamos',
            'reclamosHoy',
            'reclamosResueltosHoy',
            'reclamosPendientes',
            'porEstado',
            'porPrioridad',
            'reclamosRecientes',
            'totalEmpleados',
            'totalUsuarios',
            'tiempoPromedio'
        ));
    }
}


