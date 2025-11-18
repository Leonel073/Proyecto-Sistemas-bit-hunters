<?php

namespace App\Http\Controllers;

use App\Models\Reclamo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReclamoResolucionController extends Controller
{
    /**
     * Procesa la resolución de un reclamo por parte del Técnico.
     */
    public function resolver(Request $request, Reclamo $reclamo)
    {
        // Verificación de autenticación y rol (aunque ya lo hace el middleware)
        $tecnico = Auth::guard('empleado')->user();
        if (!$tecnico || $tecnico->rol !== 'Tecnico') {
            return back()->withErrors('Acceso denegado.');
        }

        // 1. Validar los datos de la solución
        $request->validate([
            'solucionTecnica' => 'required|string|min:10', // Usamos el nombre del campo del modelo
        ]);

        // 2. Verificar que el reclamo esté asignado a este Técnico
        if ($reclamo->idTecnicoAsignado !== $tecnico->idEmpleado) {
            return back()->withErrors('Este reclamo no está asignado a usted.');
        }

        // 3. Actualizar el registro del reclamo
        $reclamo->solucionTecnica = $request->solucionTecnica;
        $reclamo->estado = 'Resuelto'; // Cambiar el estado
        $reclamo->fechaResolucion = now(); // Registrar la hora actual
        $reclamo->fechaActualizacion = now(); // Actualizar el timestamp
        
        $reclamo->save();

        return redirect()->route('tecnico.dashboard')->with('success', 'El Reclamo #'.$reclamo->idReclamo.' ha sido marcado como Resuelto.');
    }
}