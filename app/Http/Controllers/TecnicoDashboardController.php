<?php

namespace App\Http\Controllers;

use App\Models\Reclamo;
use App\Models\Tecnico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importar el modelo Técnico

class TecnicoDashboardController extends Controller
{
    /**
     * Muestra la lista de reclamos asignados al técnico autenticado.
     */
    public function index()
    {
        // 1. Obtener el objeto Empleado completo del técnico logueado. Este contiene 'primerNombre' y 'apellidoPaterno'.
        $tecnico = Auth::user();
        $tecnicoId = $tecnico->idEmpleado;

        // 2. Obtener el estado actual de disponibilidad del técnico a partir de su perfil 'Tecnico'.
        // Este registro es necesario para obtener 'estadoDisponibilidad'.
        $tecnicoProfile = Tecnico::where('idEmpleado', $tecnicoId)->first();
        $estadoActual = $tecnicoProfile ? $tecnicoProfile->estadoDisponibilidad : 'Disponible';

        // 3. Filtrar los reclamos por el ID del técnico y por estados activos.
        $reclamos = Reclamo::where('idTecnicoAsignado', $tecnicoId)
            ->whereNotIn('estado', ['Cerrado', 'Resuelto', 'Completado']) // Añade los estados finales que uses
            ->orderBy('prioridad', 'desc')
            ->orderBy('fechaCreacion', 'asc')
            ->get();

        // 4. Pasar todas las variables necesarias a la vista, incluyendo el objeto $tecnico (Empleado).
        return view('tecnico.dashboard', compact('reclamos', 'estadoActual', 'tecnico'));
    }

    /**
     * Permite al técnico cambiar su estado de disponibilidad.
     */
    public function actualizarEstadoDisponibilidad(Request $request)
    {
        $request->validate([
            'estadoDisponibilidad' => 'required|in:Disponible,En Ruta,Ocupado',
        ]);

        $tecnicoId = Auth::user()->idEmpleado;
        // Aquí se busca el perfil Tecnico, lo cual es correcto para actualizar el estado.
        $tecnicoProfile = Tecnico::where('idEmpleado', $tecnicoId)->first();

        if ($tecnicoProfile) {
            $tecnicoProfile->estadoDisponibilidad = $request->estadoDisponibilidad;
            $tecnicoProfile->save();

            return redirect()->route('tecnico.dashboard')->with('success', 'Estado de disponibilidad actualizado a '.$tecnicoProfile->estadoDisponibilidad.'.');
        }

        return redirect()->route('tecnico.dashboard')->with('error', 'Error: No se encontró el perfil de técnico asociado a su cuenta.');
    }

    /**
     * Permite al técnico cambiar el estado de un reclamo a 'En Proceso'.
     */
    public function aceptarReclamo($idReclamo)
    {
        $tecnicoId = Auth::user()->idEmpleado;
        $reclamo = Reclamo::where('idReclamo', $idReclamo)
            ->where('idTecnicoAsignado', $tecnicoId)
            ->first();

        if (! $reclamo) {
            return redirect()->route('tecnico.dashboard')->with('error', 'Reclamo no encontrado o no asignado a usted.');
        }

        // Lógica para aceptar: cambiar el estado y registrar la fecha de actualización
        if ($reclamo->estado === 'Asignado' || $reclamo->estado === 'Pendiente') {
            $reclamo->estado = 'En Proceso';
            $reclamo->fechaActualizacion = now();
            $reclamo->save();

            return redirect()->route('tecnico.dashboard')->with('success', "Reclamo #{$idReclamo} aceptado y en proceso.");
        }

        return redirect()->route('tecnico.dashboard')->with('info', "El reclamo #{$idReclamo} ya se encuentra en proceso o resuelto.");
    }
}
