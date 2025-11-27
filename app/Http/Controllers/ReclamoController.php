<?php

namespace App\Http\Controllers;

use App\Models\Reclamo;
use App\Models\Usuario; // Importado para relación
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ReclamoController extends Controller
{
    /**
     * Procesa el formulario de reclamo enviado por el CLIENTE (Ruta: reclamo.store)
     * y guarda el registro en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Verificar Autenticación
        if (!Auth::check()) {
            // Ya que la ruta está protegida por 'auth', este check es un seguro extra.
            return back()->withErrors(['error' => 'Debe iniciar sesión para crear un reclamo.']);
        }
        
        // 2. VALIDACIÓN: Solo validamos los campos que vienen del formulario.
        // Las validaciones de idPoliticaSLA y prioridad se REMUEVEN del required.
        $request->validate([
            'titulo'               => 'required|string|max:255',
            'idTipoIncidente'      => 'required|integer|exists:cat_tipo_incidente,idTipoIncidente', // Debe existir en el catálogo
            'descripcionDetallada' => 'required|string',
            'latitudIncidente'     => 'required|numeric',
            'longitudIncidente'    => 'required|numeric',
            
            // ❌ Se remueven las validaciones de PoliticaSLA y Prioridad, ya que se asignan por defecto.
        ]);

        try {
            // 3. Obtener el ID del usuario logueado
            $idUsuario = Auth::id(); // Usa el ID de la sesión

            // 4. GUARDAR EL RECLAMO
            $reclamo = new Reclamo();
            
            // Asignaciones directas del formulario
            $reclamo->idUsuario            = $idUsuario;
            $reclamo->titulo               = $request->titulo;
            $reclamo->descripcionDetallada = $request->descripcionDetallada;
            $reclamo->idTipoIncidente      = $request->idTipoIncidente;
            $reclamo->latitudIncidente     = $request->latitudIncidente;
            $reclamo->longitudIncidente    = $request->longitudIncidente;
            
            // ✅ Asignaciones Fijas (Reglas de Negocio)
            // Esto soluciona el error 'id politica s l a field is required'
            $reclamo->idPoliticaSLA        = 1;         // Asignar ID de la política más básica (Debe existir en la DB)
            $reclamo->prioridad            = 'Media';   // Prioridad base que el operador ajustará
            $reclamo->estado               = 'Nuevo';

            $reclamo->save();

            // 5. Redirección con éxito
            return back()->with('success', 'Su reclamo ha sido registrado correctamente.');

        } catch (ValidationException $e) {
             // Si hay errores de validación de base de datos (e.g. FK no existe)
             return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
             // Cualquier otro error, como un fallo de conexión
             return back()->withErrors(['error' => 'Error al guardar: ' . $e->getMessage()])->withInput();
        }
    }
    
    // NOTA: Se asume que todas las demás funciones (index, show, etc.)
    // que devuelven JSON o vistas siguen existiendo en el controlador.
}