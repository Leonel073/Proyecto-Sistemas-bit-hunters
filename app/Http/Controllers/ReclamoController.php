<?php

namespace App\Http\Controllers;

use App\Models\Reclamo;
use App\Models\Usuario; // Importado para relación
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ReclamoController extends Controller
{
    /**
     * Muestra el formulario de creación de reclamo.
     * Si es Admin/Empleado, permite seleccionar el usuario.
     */
    public function create()
    {
        $usuarios = null;
        
        // Si es empleado (Admin/Gerente/etc), cargamos la lista de usuarios
        if (Auth::guard('empleado')->check()) {
            $usuarios = Usuario::orderBy('idUsuario')->take(1)->get();
        }

        return view('cliente.create', compact('usuarios'));
    }

    /**
     * Muestra el seguimiento de reclamos.
     * Si es Admin/Empleado, muestra TODOS los reclamos.
     * Si es Cliente, muestra solo SUS reclamos.
     */
    public function seguimiento()
    {
        if (Auth::guard('empleado')->check()) {
            // Admin ve todos los reclamos
            $reclamos = Reclamo::with('usuario', 'tipoIncidente', 'tecnico')
                ->orderBy('fechaCreacion', 'desc')
                ->get();
        } else {
            // Cliente ve solo sus reclamos
            $usuario = Auth::user();
            $reclamos = Reclamo::where('idUsuario', $usuario->idUsuario)
                ->with('tipoIncidente', 'tecnico')
                ->orderBy('fechaCreacion', 'desc')
                ->get();
        }

        return view('cliente.seguimiento', compact('reclamos'));
    }

    /**
     * Procesa el formulario de reclamo enviado por el CLIENTE (Ruta: reclamo.store)
     * y guarda el registro en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Verificar Autenticación (Web o Empleado)
        if (!Auth::check() && !Auth::guard('empleado')->check()) {
            return back()->withErrors(['error' => 'Debe iniciar sesión para crear un reclamo.']);
        }

        // 2. VALIDACIÓN: Solo validamos los campos que vienen del formulario.
        // Las validaciones de idPoliticaSLA y prioridad se REMUEVEN del required.
        $messages = [
            'titulo.required' => 'El título del reclamo es obligatorio.',
            'titulo.max' => 'El título no puede exceder los 255 caracteres.',
            'idTipoIncidente.required' => 'Debe seleccionar una categoría de incidente.',
            'idTipoIncidente.exists' => 'La categoría seleccionada no es válida.',
            'descripcionDetallada.required' => 'La descripción detallada es obligatoria.',
            'latitudIncidente.required' => 'Debe seleccionar la ubicación en el mapa.',
            'longitudIncidente.required' => 'Debe seleccionar la ubicación en el mapa.',
        ];

        $request->validate([
            'titulo' => 'required|string|max:255',
            'idTipoIncidente' => 'required|integer|exists:cat_tipo_incidente,idTipoIncidente', // Debe existir en el catálogo
            'descripcionDetallada' => 'required|string',
            'latitudIncidente' => 'required|numeric',
            'longitudIncidente' => 'required|numeric',

            // ❌ Se remueven las validaciones de PoliticaSLA y Prioridad, ya que se asignan por defecto.
        ], $messages);

        try {
            // 3. Obtener el ID del usuario
            if (Auth::guard('empleado')->check()) {
                // Si es empleado, debe venir el idUsuario en el request
                $request->validate(['idUsuario' => 'required|exists:usuarios,idUsuario']);
                $idUsuario = $request->idUsuario;
            } else {
                // Si es cliente, usamos su ID de sesión
                $idUsuario = Auth::id();
            }

            // 4. GUARDAR EL RECLAMO
            $reclamo = new Reclamo;

            // Asignaciones directas del formulario
            $reclamo->idUsuario = $idUsuario;
            $reclamo->titulo = $request->titulo;
            $reclamo->descripcionDetallada = $request->descripcionDetallada;
            $reclamo->idTipoIncidente = $request->idTipoIncidente;
            $reclamo->latitudIncidente = $request->latitudIncidente;
            $reclamo->longitudIncidente = $request->longitudIncidente;

            // ✅ Asignaciones Fijas (Reglas de Negocio)
            // Esto soluciona el error 'id politica s l a field is required'
            $reclamo->idPoliticaSLA = 1;         // Asignar ID de la política más básica (Debe existir en la DB)
            $reclamo->prioridad = 'Media';   // Prioridad base que el operador ajustará
            $reclamo->estado = 'Nuevo';

            $reclamo->save();

            // 5. Redirección con éxito
            return back()->with('success', 'Su reclamo ha sido registrado correctamente.');

        } catch (ValidationException $e) {
            // Si hay errores de validación de base de datos (e.g. FK no existe)
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Cualquier otro error, como un fallo de conexión
            return back()->withErrors(['error' => 'Error al guardar: '.$e->getMessage()])->withInput();
        }
    }

    // NOTA: Se asume que todas las demás funciones (index, show, etc.)
    // que devuelven JSON o vistas siguen existiendo en el controlador.
}
