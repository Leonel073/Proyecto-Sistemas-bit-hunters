<?php

namespace App\Http\Controllers;

use App\Models\Tecnico;
use Illuminate\Http\Request;

class TecnicoController extends Controller
{
    public function index()
    {
<<<<<<< Updated upstream
        return response()->json(Tecnico::all());
    }

    public function store(Request $request)
=======
        $empleadoId = Auth::guard('empleado')->id();
       
        // Obtener el perfil del técnico
        $tecnico = Tecnico::where('idEmpleado', $empleadoId)->firstOrFail();
        $estadoActual = $tecnico->estadoDisponibilidad;

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
            'estadoDisponibilidad' => 'required|string|in:Disponible,En Ruta,Ocupado'
        ]);

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
            if (!in_array($reclamo->estado, ['Asignado', 'Nuevo'])) {
                return redirect()->back()->with('error', 'El estado actual del reclamo (' . $reclamo->estado . ') no permite la acción de Aceptar.');
            }

            // Actualizar estado
            $reclamo->estado = 'En Proceso';
            $reclamo->fechaActualizacion = now();
            $reclamo->save();

            return redirect()->route('tecnico.dashboard')->with('success', "Reclamo #{$reclamo->idReclamo} ha sido puesto en 'En Proceso'.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al procesar la aceptación del reclamo: ' . $e->getMessage());
        }
    }

    /**
     * El técnico registra la solución y marca el reclamo como "Resuelto".
     */
    public function resolverReclamo(Request $request, Reclamo $reclamo)
>>>>>>> Stashed changes
    {
        $request->validate([
            'idEmpleado' => 'required|integer|unique:tecnicos,idEmpleado',
            'especialidad' => 'required|string|max:100',
            'estadoDisponibilidad' => 'nullable|string'
        ]);
<<<<<<< Updated upstream
<<<<<<< Updated upstream

        $tecnico = Tecnico::create($request->all());
        return response()->json(['message' => 'Técnico registrado correctamente', 'data' => $tecnico]);
=======
=======
>>>>>>> Stashed changes
       
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

            return redirect()->route('tecnico.dashboard')->with('success', "Reclamo #{$reclamo->idReclamo} ha sido marcado como 'Resuelto'.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al registrar la solución: ' . $e->getMessage());
        }
>>>>>>> Stashed changes
    }

    public function show($id)
    {
        return response()->json(Tecnico::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $tecnico = Tecnico::findOrFail($id);
        $tecnico->update($request->all());
        return response()->json(['message' => 'Técnico actualizado', 'data' => $tecnico]);
    }

    public function destroy($id)
    {
        Tecnico::destroy($id);
        return response()->json(['message' => 'Técnico eliminado']);
    }
}
