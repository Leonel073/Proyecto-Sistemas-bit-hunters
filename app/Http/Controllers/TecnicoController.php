<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reclamo;
use App\Models\Tecnico;

class TecnicoController extends Controller
{
    /**
     * Muestra el panel principal del técnico con sus reclamos asignados.
     */
    public function panel()
    {
        $empleadoId = Auth::guard('empleado')->id();
        
        // 1. Obtener el modelo del Técnico para el estado actual
        $tecnico = Tecnico::where('idEmpleado', $empleadoId)->firstOrFail();
        $estadoActual = $tecnico->estadoDisponibilidad;

        // 2. Obtener los reclamos asignados a este técnico
        $reclamos = Reclamo::where('idtecnicoasignado', $empleadoId) // Usamos el nombre de columna correcto
            ->whereNotIn('estado', ['Cerrado', 'Resuelto', 'Completado'])
            ->with(['usuario', 'operador']) // ¡Esta línea ahora funciona!
            ->orderBy('prioridad', 'desc')
            ->orderBy('fechaCreacion', 'asc')
            ->get();

        return view('tecnico.dashboard', compact('tecnico', 'estadoActual', 'reclamos'));
    }

    /**
     * Actualiza el estado de disponibilidad del técnico.
     */
    public function actualizarEstado(Request $request)
    {
        $request->validate(['estadoDisponibilidad' => 'required|string']);

        try {
            $empleadoId = Auth::guard('empleado')->id();
            $tecnico = Tecnico::where('idEmpleado', $empleadoId)->firstOrFail();
            
            $tecnico->estadoDisponibilidad = $request->estadoDisponibilidad;
            $tecnico->save();

            return redirect()->route('tecnico.dashboard')->with('success', 'Tu estado ha sido actualizado a ' . $request->estadoDisponibilidad . '.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar estado.');
        }
    }

    /**
     * El técnico acepta el reclamo y lo pone en estado "En Proceso".
     *
     * @param \App\Models\Reclamo $reclamo
     */
    public function aceptarReclamo(Reclamo $reclamo)
    {
        $empleadoId = Auth::guard('empleado')->id();
        
        // Forzamos la recarga del modelo desde la BD para asegurar que los datos no estén "stale"
        $reclamo->refresh();

        // Obtenemos los IDs. No necesitamos forzar a entero si usamos comparación no estricta, 
        // pero lo mantenemos para claridad.
        $tecnicoAsignadoId = $reclamo->idtecnicoasignado; // Puede ser NULL o un número (string/int)
        $empleadoIdNumerico = (int) $empleadoId;

        try {
            // 1. Verificar si el reclamo está asignado a este técnico.
            // Usamos comparación NO ESTRICTA (!=) para evitar problemas de tipo (string vs int).
            if ($tecnicoAsignadoId != $empleadoIdNumerico) { 
                
                // Si es NULL, se compara como 0, lo que causaba el error original.
                // Si tu ID es 3 y la BD tiene NULL, la comparación falla (NULL != 3).
                $motivo = 'No está asignado a tu cuenta (Reclamo ID #' . $reclamo->idReclamo . ': ' . ($tecnicoAsignadoId ?? 'NULL') . ' vs Tu ID Autenticado: ' . $empleadoIdNumerico . ')';
                
                return redirect()->back()->with('error', 'El reclamo no puede ser aceptado. Motivo: ' . $motivo);
            }
            
            // 2. Verificar que el estado sea el correcto para la transición
            if (!in_array($reclamo->estado, ['Asignado', 'Pendiente'])) {
                $motivo = 'El estado actual del reclamo (' . $reclamo->estado . ') no permite la acción de Aceptar.';
                return redirect()->back()->with('error', 'El reclamo no puede ser aceptado. Motivo: ' . $motivo);
            }

            // Si pasa las validaciones, actualizamos
            $reclamo->estado = 'En Proceso';
            $reclamo->save();

            return redirect()->route('tecnico.dashboard')->with('success', "Reclamo #{$reclamo->idReclamo} ha sido puesto en 'En Proceso'.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al procesar la aceptación del reclamo: ' . $e->getMessage());
        }
    }

    /**
     * El técnico registra la solución y marca el reclamo como "Resuelto".
     *
     * @param \App\Models\Reclamo $reclamo
     */
    public function resolverReclamo(Request $request, Reclamo $reclamo)
    {
        $request->validate([
            'solucionTecnica' => 'required|string|min:10',
        ]);
        
        $empleadoId = Auth::guard('empleado')->id();

        try {
            // Usamos comparación NO ESTRICTA (!=) para consistencia
            if ($reclamo->idtecnicoasignado != $empleadoId || $reclamo->estado !== 'En Proceso') {
                 return redirect()->back()->with('error', 'El reclamo no está en el estado o asignación correcta para ser resuelto.');
            }

            // Guardar la solución técnica y la fecha de resolución
            $reclamo->solucionTecnica = $request->solucionTecnica; 
            
            $reclamo->estado = 'Resuelto';
            $reclamo->fechaResolucion = now(); // Agregar fecha de resolución
            $reclamo->save();

            return redirect()->route('tecnico.dashboard')->with('success', "Reclamo #{$reclamo->idReclamo} ha sido marcado como 'Resuelto'.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al registrar la solución: ' . $e->getMessage());
        }
    }
}