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
        return response()->json(Tecnico::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'idEmpleado' => 'required|integer|unique:tecnicos,idEmpleado',
            'especialidad' => 'required|string|max:100',
            'estadoDisponibilidad' => 'nullable|string'
        ]);

        $tecnico = Tecnico::create($request->all());
        return response()->json(['message' => 'Técnico registrado correctamente', 'data' => $tecnico]);
    }

    public function show($id)
    {
        return response()->json(Tecnico::findOrFail($id));
    }

    public function update(Request $request, $id)
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

            return redirect()->route('tecnico.dashboard')->with('success', "Reclamo #{$reclamo->idReclamo} ha sido marcado como 'Resuelto'.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al registrar la solución: ' . $e->getMessage());
        }
    }
}