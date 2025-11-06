<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Para la seguridad
use App\Models\Reclamo; // Para buscar y actualizar el reclamo

class ReclamoResolucionController extends Controller
{
    /**
     * Registra la solución técnica y marca un reclamo como "Resuelto".
     * La ruta es: POST /tecnico/reclamo/{reclamo}/resolver
     */
    public function resolver(Request $request, Reclamo $reclamo)
    {
        // 1. ¡SEGURIDAD PRIMERO!
        // Verificamos que el reclamo que intentan modificar REALMENTE
        // pertenezca al técnico que está logueado.
        if ($reclamo->idTecnicoAsignado !== Auth::id()) {
            // Si no le pertenece, le negamos el acceso.
            abort(403, 'Acción no autorizada. Este reclamo no está asignado a tu usuario.');
        }

        // 2. Validamos que nos hayan enviado una solución
        $request->validate([
            'solucionTecnica' => 'required|string|min:10'
        ], [
            'solucionTecnica.required' => 'Debes registrar la solución técnica aplicada.',
            'solucionTecnica.min' => 'La solución debe tener al menos 10 caracteres.'
        ]);

        // 3. Actualizamos el reclamo con los datos del formulario
        $reclamo->solucionTecnica = $request->solucionTecnica;
        $reclamo->estado = 'Resuelto';
        $reclamo->fechaResolucion = now(); // Marcamos la fecha y hora de resolución
        $reclamo->save();

        // (OPCIONAL, PERO RECOMENDADO - Lo de la auditoría que hablamos)
        // Aquí podrías guardar en la tabla 'RECLAMO_HISTORIAL_ESTADO'
        // $reclamo->historialEstados()->create([ ... ]);

        // 4. Redireccionamos al dashboard con un mensaje de éxito
        return redirect()->route('tecnico.dashboard')->with('success', 'El Reclamo R-' . $reclamo->idReclamo . ' ha sido marcado como Resuelto.');
    }
}