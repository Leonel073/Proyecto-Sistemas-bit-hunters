<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Para saber qué usuario está logueado
use App\Models\Reclamo; // Para buscar en la tabla RECLAMO
use App\Models\Tecnico; // Para buscar en la tabla TECNICO

class TecnicoDashboardController extends Controller
{
    /**
     * Muestra el panel principal del técnico con sus reclamos asignados.
     */
    public function index()
    {
        // 1. Obtenemos el ID del empleado (técnico) que está autenticado
        $idTecnicoLogueado = Auth::id();

        // 2. Buscamos sus reclamos asignados que están "pendientes"
        // (No queremos mostrar los que ya están Resueltos, Cerrados o Cancelados)
        $reclamosAsignados = Reclamo::where('idTecnicoAsignado', $idTecnicoLogueado)
                                     ->whereNotIn('estado', ['Resuelto', 'Cerrado', 'Cancelado'])
                                     ->with('usuario') // Esto es clave para ver datos del cliente
                                     ->orderBy('fechaCreacion', 'desc') // Mostrar los más nuevos primero
                                     ->get();

        // 3. Obtenemos el perfil de Técnico (para saber su estado de disponibilidad)
        // Usamos findOrFail para que falle si el Empleado no tiene un perfil de Técnico
        $tecnico = Tecnico::findOrFail($idTecnicoLogueado);

        // 4. Cargamos la vista (que crearemos en el siguiente paso) y le pasamos los datos
        return view('tecnico.dashboard', [
            'reclamos' => $reclamosAsignados,
            'estadoActual' => $tecnico->estadoDisponibilidad
        ]);
    }

    /**
     * Actualiza el estado de DISPONIBILIDAD del técnico (En Ruta, Ocupado, etc.)
     * Esto viene de la tabla TECNICO, no de RECLAMO.
     */
    public function actualizarEstadoDisponibilidad(Request $request)
    {
        // 1. Validamos que el estado que nos envían sea uno de los permitidos
        $request->validate([
            'estadoDisponibilidad' => 'required|in:Disponible,En Ruta,Ocupado'
        ]);

        // 2. Buscamos el registro del técnico
        $tecnico = Tecnico::findOrFail(Auth::id());

        // 3. Actualizamos su estado y guardamos
        $tecnico->estadoDisponibilidad = $request->estadoDisponibilidad;
        $tecnico->save();

        // 4. Redireccionamos de vuelta al dashboard con un mensaje de éxito
        return back()->with('success', 'Tu estado ha sido actualizado a "' . $request->estadoDisponibilidad . '".');
    }

    /**
     * Función que permite al técnico cambiar el estado de un reclamo a 'En Proceso' o 'Pausado'.
     */
    public function actualizarProgreso(Request $request, $idReclamo)
    {
        $request->validate([
            'nuevoEstado' => 'required|in:En Proceso,Pausado',
        ]);

        $reclamo = Reclamo::findOrFail($idReclamo);
        
        // Seguridad: Verificar que el reclamo esté asignado al técnico logueado
        $idTecnicoLogueado = Auth::id();

        if ($reclamo->idTecnicoAsignado !== $idTecnicoLogueado) {
             return back()->withErrors(['error' => 'No tienes permiso para modificar este reclamo.']);
        }
        
        // El reclamo no puede ser actualizado si ya está cerrado o resuelto
        if (in_array($reclamo->estado, ['Resuelto', 'Cerrado', 'Cancelado'])) {
            return back()->withErrors(['error' => 'No se puede modificar el progreso de un reclamo finalizado.']);
        }

        // Actualizar el estado
        $reclamo->update([
            'estado' => $request->nuevoEstado,
        ]);

        return back()->with('success', "Progreso del Reclamo #R-$idReclamo actualizado a: {$request->nuevoEstado}");
    }


    /**
     * Marca un reclamo como resuelto.
     * Corresponde a la ruta POST tecnico.reclamo.resolver
     */
    public function resolverReclamo(Request $request, $idReclamo)
    {
        // 1. Validar la solución técnica
        $request->validate([
            'solucionTecnica' => 'required|string|min:10',
        ]);

        $reclamo = Reclamo::findOrFail($idReclamo);
        
        // 2. Seguridad: Verificar que el reclamo esté asignado al técnico logueado
        $idTecnicoLogueado = Auth::id();

        if ($reclamo->idTecnicoAsignado !== $idTecnicoLogueado) {
             return back()->withErrors(['error' => 'No tienes permiso para resolver este reclamo.']);
        }

        // 3. Actualizar el estado del reclamo
        $reclamo->update([
            'estado' => 'Resuelto', // Cambia el estado final del reclamo
            'solucionTecnica' => $request->solucionTecnica, // Registra la solución
            'fechaSolucion' => now(), // Marca la fecha de solución
        ]);
        
        // 4. Redireccionar con mensaje
        return back()->with('success', "¡Reclamo #R-$idReclamo marcado como RESUELTO!");
    }
}