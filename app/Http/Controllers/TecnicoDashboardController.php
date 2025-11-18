<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Para saber qué usuario está logueado
use App\Models\Reclamo; // Para buscar en la tabla RECLAMO
use App\Models\Tecnico; // Para buscar en la tabla TECNICO
use App\Models\Empleado; // Importar el modelo Empleado para obtener los datos del usuario

class TecnicoDashboardController extends Controller
{
    /**
     * Muestra el panel principal del técnico con sus reclamos asignados.
     */
    public function index()
    {
        // 1. Obtenemos el ID del empleado (técnico) que está autenticado
        $idTecnicoLogueado = Auth::id();

        // 2. Buscamos el objeto Empleado completo para mostrar el nombre
        $empleado = Empleado::findOrFail($idTecnicoLogueado);

        // 3. Buscamos sus reclamos asignados que están "pendientes"
        $reclamosAsignados = Reclamo::where('idTecnicoAsignado', $idTecnicoLogueado)
                                    ->whereNotIn('estado', ['Resuelto', 'Cerrado', 'Cancelado'])
                                    ->with('usuario') // Cargar el nombre del cliente
                                    ->orderBy('fechaCreacion', 'desc') 
                                    ->get();

        // 4. Obtenemos el perfil de Técnico (para saber su estado de disponibilidad)
        $tecnicoPerfil = Tecnico::findOrFail($idTecnicoLogueado);

        // 5. Cargamos la vista con los datos
        return view('tecnico.dashboard', [
            'reclamos' => $reclamosAsignados,
            'estadoActual' => $tecnicoPerfil->estadoDisponibilidad, // El estado que usará el botón
            'tecnico' => $empleado // El objeto Empleado completo para el título
        ]);
    }

    /**
     * Actualiza el estado de DISPONIBILIDAD del técnico (Disponible, En Ruta, Ocupado)
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
     * Marca un reclamo como resuelto.
     */
    public function resolverReclamo(Request $request, $idReclamo)
    {
        $request->validate([
            'solucionTecnica' => 'required|string|min:10',
        ]);

        try {
            $reclamo = Reclamo::findOrFail($idReclamo);

            // Asegurar que el técnico que resuelve es el asignado
            if ($reclamo->idTecnicoAsignado != Auth::id()) {
                return back()->with('error', 'No tienes permiso para resolver este reclamo. No te fue asignado.');
            }

            $reclamo->estado = 'Resuelto';
            $reclamo->fechaResolucion = now();
            $reclamo->solucionTecnica = $request->solucionTecnica;
            $reclamo->save();

            // Opcional: Crear un log de la acción si tienes una tabla de logs
            // Log::info("Reclamo $idReclamo resuelto por técnico $reclamo->idTecnicoAsignado");

            return back()->with('success', '¡Reclamo #'.$idReclamo.' marcado como Resuelto con éxito!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al intentar resolver el reclamo: ' . $e->getMessage());
        }
    }
}