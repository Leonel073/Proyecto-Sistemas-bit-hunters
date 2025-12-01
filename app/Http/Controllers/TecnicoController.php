<?php

namespace App\Http\Controllers;

use App\Models\Reclamo;
use App\Models\Tecnico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TecnicoController extends Controller
{
    /**
     * Muestra el panel principal del técnico con sus reclamos asignados.
     */
    public function panel()
    {
        $empleadoId = Auth::guard('empleado')->id();

        // Obtener el perfil del técnico (puede ser null si es SuperAdmin)
        $tecnico = Tecnico::where('idEmpleado', $empleadoId)->first();
        $estadoActual = $tecnico ? $tecnico->estadoDisponibilidad : 'No Aplicable';

        // CORREGIDO: Usar el nombre correcto de la columna
        $reclamos = Reclamo::where('idTecnicoAsignado', $empleadoId)
            ->whereNotIn('estado', ['Cerrado', 'Resuelto', 'Completado'])
            ->with(['usuario', 'operador'])
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
        $request->validate([
            'estadoDisponibilidad' => 'required|string|in:Disponible,En Ruta,Ocupado',
        ]);

        try {
            $empleadoId = Auth::guard('empleado')->id();
            $tecnico = Tecnico::where('idEmpleado', $empleadoId)->firstOrFail();

            $tecnico->estadoDisponibilidad = $request->estadoDisponibilidad;
            $tecnico->save();

            return redirect()->route('tecnico.dashboard')->with('success', 'Tu estado ha sido actualizado a '.$request->estadoDisponibilidad.'.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar estado.');
        }
    }

    /**
     * El técnico acepta el reclamo y lo pone en estado "En Proceso".
     */
    public function aceptarReclamo(Reclamo $reclamo)
    {
        $empleadoId = Auth::guard('empleado')->id();

        // Recargar el modelo para asegurar datos actualizados
        $reclamo->refresh();

        try {
            // CORREGIDO: Verificación simplificada
            if ($reclamo->idTecnicoAsignado != $empleadoId) {
                return redirect()->back()->with('error', 'Este reclamo no está asignado a tu cuenta.');
            }

            // CORREGIDO: Verificación de estado
            if (! in_array($reclamo->estado, ['Asignado', 'Nuevo'])) {
                return redirect()->back()->with('error', 'El estado actual del reclamo ('.$reclamo->estado.') no permite la acción de Aceptar.');
            }

            // Actualizar estado del reclamo
            $reclamo->estado = 'En Proceso';
            $reclamo->fechaActualizacion = now();
            $reclamo->save();

            // Actualizar estado del técnico a 'Ocupado' (o 'En Ruta')
            $tecnico = Tecnico::where('idEmpleado', $empleadoId)->first();
            if ($tecnico) {
                $tecnico->estadoDisponibilidad = 'Ocupado';
                $tecnico->save();
            }

            return redirect()->route('tecnico.dashboard')->with('success', "Reclamo #{$reclamo->idReclamo} aceptado. Tu estado ahora es 'Ocupado'.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al procesar la aceptación del reclamo: '.$e->getMessage());
        }
    }

    /**
     * El técnico registra la solución y marca el reclamo como "Resuelto".
     */
    public function resolverReclamo(Request $request, Reclamo $reclamo)
    {
        $request->validate([
            'solucionTecnica' => 'required|string|min:10',
        ]);

        $empleadoId = Auth::guard('empleado')->id();

        try {
            // Verificar asignación y estado
            if ($reclamo->idTecnicoAsignado != $empleadoId || $reclamo->estado !== 'En Proceso') {
                return redirect()->back()->with('error', 'El reclamo no está en el estado o asignación correcta para ser resuelto.');
            }

            // Guardar la solución
            $reclamo->solucionTecnica = $request->solucionTecnica;
            $reclamo->estado = 'Resuelto';
            $reclamo->fechaResolucion = now();
            $reclamo->fechaActualizacion = now();
            $reclamo->save();

            $reclamo->fechaActualizacion = now();
            $reclamo->save();

            // Actualizar estado del técnico a 'Disponible'
            $tecnico = Tecnico::where('idEmpleado', $empleadoId)->first();
            if ($tecnico) {
                $tecnico->estadoDisponibilidad = 'Disponible';
                $tecnico->save();
            }

            return redirect()->route('tecnico.dashboard')->with('success', "Reclamo #{$reclamo->idReclamo} ha sido marcado como 'Resuelto' y tu estado ahora es 'Disponible'.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al registrar la solución: '.$e->getMessage());
        }
    }
}
