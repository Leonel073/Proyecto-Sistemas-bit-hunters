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
}