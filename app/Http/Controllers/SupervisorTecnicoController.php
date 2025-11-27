<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Tecnico;
use App\Models\SupervisorTecnico; // Lo mantenemos por si lo usas en 'show'
use App\Models\Reclamo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SupervisorTecnicoController extends Controller
{
    // --- MÉTODOS PARA VISTAS BLADE ---

    /** Muestra el panel de gestión de Técnicos */
    public function index()
    {
        $tecnicos = Empleado::with('tecnico')
            ->where('rol', 'Tecnico')
            ->where('estado', '!=', 'Eliminado')
            ->whereNull('fechaEliminacion')
            ->get();
        
        return view('supervisor_tecnicos.tecnicos', compact('tecnicos'));
    }

    /** Muestra el formulario para crear un nuevo Técnico */
    public function create()
    {
        return view('supervisor_tecnicos.create');
    }

    /** Guarda el nuevo Técnico en la BD */
    public function store(Request $request)
    {
         $request->validate([
            'primerNombre' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'segundoNombre' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'apellidoPaterno' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'apellidoMaterno' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'ci' => 'required|numeric|digits_between:7,10|unique:empleados,ci',
            'numeroCelular' => 'required|numeric|unique:empleados,numeroCelular',
            'emailCorporativo' => 'required|email|max:255|unique:empleados,emailCorporativo',
            'fechaIngreso' => 'required|date',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ], [
            // (Mensajes de error en español)
            'primerNombre.required' => 'El primer nombre es obligatorio.',
            'segundoNombre.required' => 'El segundo nombre es obligatorio.',
            'apellidoPaterno.required' => 'El apellido paterno es obligatorio.',
            'apellidoMaterno.required' => 'El apellido materno es obligatorio.',
            'ci.required' => 'El campo CI es obligatorio.',
            'ci.numeric' => 'El CI debe contener solo números.',
            'ci.unique' => 'Este CI ya está registrado.',
            'numeroCelular.required' => 'El número de celular es obligatorio.',
            'numeroCelular.numeric' => 'El celular debe contener solo números.',
            'numeroCelular.unique' => 'Este número de celular ya está registrado.',
            'emailCorporativo.required' => 'El correo electrónico es obligatorio.',
            'emailCorporativo.unique' => 'Este correo electrónico ya está registrado.',
            'fechaIngreso.required' => 'La fecha de ingreso es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $empleado = Empleado::create([
            'primerNombre' => $request->primerNombre,
            'segundoNombre' => $request->segundoNombre,
            'apellidoPaterno' => $request->apellidoPaterno,
            'apellidoMaterno' => $request->apellidoMaterno,
            'ci' => $request->ci,
            'numeroCelular' => $request->numeroCelular,
            'emailCorporativo' => $request->emailCorporativo,
            'passwordHash' => Hash::make($request->password),
            'rol' => 'Tecnico', // Rol predeterminado
            'estado' => 'Activo',
            'fechaIngreso' => $request->fechaIngreso,
        ]);

        Tecnico::create([
            'idEmpleado' => $empleado->idEmpleado,
            'especialidad' => $request->especialidad ?? 'General' // Asigna (si lo añades al form) o usa 'General'
        ]);

        return redirect()->route('supervisor.tecnicos.index')->with('success', 'Técnico registrado correctamente.');
    }

    /** Muestra el formulario para editar un Técnico */
    public function edit($id)
    {
        $empleado = Empleado::where('idEmpleado', $id)->where('rol', 'Tecnico')->firstOrFail();
        return view('supervisor_tecnicos.edit', compact('empleado'));
    }

    /** Actualiza un Técnico */
    public function update(Request $request, $id)
    {
        $empleado = Empleado::where('idEmpleado', $id)->where('rol', 'Tecnico')->firstOrFail();

        $request->validate([
            'primerNombre' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'segundoNombre' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'apellidoPaterno' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'apellidoMaterno' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'emailCorporativo' => [
                'required', 'email', 'max:150',
                Rule::unique('empleados')->ignore($empleado->idEmpleado, 'idEmpleado')
            ],
            'estado' => 'required|string|in:Activo,Bloqueado,Eliminado',
        ]);

        $empleado->update([
            'primerNombre' => $request->primerNombre,
            'segundoNombre' => $request->segundoNombre,
            'apellidoPaterno' => $request->apellidoPaterno,
            'apellidoMaterno' => $request->apellidoMaterno,
            'emailCorporativo' => $request->emailCorporativo,
            'estado' => $request->estado,
        ]);

        return redirect()->route('supervisor.tecnicos.index')->with('success', 'Técnico actualizado correctamente.');
    }

    /** Muestra la lista de Técnicos eliminados */
    public function deleted()
    {
        $tecnicos = Empleado::where('rol', 'Tecnico')
                            ->where('estado', 'Eliminado')
                            ->get();
        return view('supervisor_tecnicos.deleted', compact('tecnicos'));
    }

    /** Restaura un Técnico eliminado */
    public function restore($id)
    {
        $empleado = Empleado::where('idEmpleado', $id)->where('rol', 'Tecnico')->firstOrFail();
        $empleado->update([
            'estado' => 'Activo',
            'fechaEliminacion' => null,
        ]);

        return redirect()->route('supervisor.tecnicos.deleted')->with('success', 'Técnico reactivado correctamente.');
    }

    /** Elimina (soft delete) un Técnico */
    public function destroy($id)
    {
        $empleado = Empleado::where('idEmpleado', $id)->where('rol', 'Tecnico')->firstOrFail();
        $empleado->update([
            'estado' => 'Eliminado',
            'fechaEliminacion' => now(),
        ]);

        return redirect()->route('supervisor.tecnicos.index')->with('success', 'Técnico eliminado correctamente.');
    }
    
    // --- MÉTODOS DE API JSON (Si aún los necesitas) ---
    
    /** Devuelve JSON de un supervisor específico (padre) */
    public function show($id)
    {
        // Esto busca en la tabla 'supervisores_tecnicos', no en 'empleados'
        return response()->json(SupervisorTecnico::findOrFail($id));
    }

    // =========================================================
    // FUNCIONES DE REASIGNACIÓN (Dashboard)
    // =========================================================

    /**
     * Muestra el dashboard con los reclamos asignados a técnicos para reasignar.
     * (Función para la ruta supervisor.tecnicos.dashboard)
     */
    public function dashboard()
    {
        // 1. Reclamos que tienen técnico asignado (para poder reasignarlos)
        // Usar with() con callbacks para manejar relaciones que podrían no existir
        $reclamosAsignados = Reclamo::whereNotNull('idTecnicoAsignado')
                                ->whereIn('estado', ['Asignado', 'En Proceso', 'Abierto'])
                                ->with([
                                    'usuario',
                                    'tecnico' => function($query) {
                                        $query->where('estado', '!=', 'Eliminado');
                                    },
                                    'operador' => function($query) {
                                        $query->where('estado', '!=', 'Eliminado');
                                    }
                                ])
                                ->orderBy('fechaCreacion', 'desc')
                                ->get();

        // 2. Lista de Técnicos activos para el select
        $tecnicosActivos = Empleado::where('rol', 'Tecnico')
                                    ->where('estado', 'Activo')
                                    ->with('tecnico') // Cargar relación para obtener especialidad
                                    ->orderBy('apellidoPaterno')
                                    ->get();

        // 3. Devolvemos la vista con los datos
        return view('supervisor_tecnicos.dashboard_reasignar', [
            'reclamos' => $reclamosAsignados,
            'tecnicos' => $tecnicosActivos,
            // Obtenemos el usuario supervisor autenticado
            'supervisor' => Auth::guard('empleado')->user() 
        ]);
    }

    /**
     * Procesa la reasignación de un reclamo a un nuevo técnico.
     * (Función para la ruta supervisor.tecnicos.reasignar)
     */
    public function reasignarTecnico(Request $request, $reclamo)
    {
        try {
            // 1. Buscar el reclamo manualmente para evitar problemas con route model binding
            $reclamo = Reclamo::findOrFail($reclamo);
            
            // 2. Validación
            $data = $request->validate([
                'idTecnico' => 'required|integer|exists:empleados,idEmpleado', // ID del nuevo técnico
                'prioridad' => 'nullable|string|in:Baja,Media,Alta,Urgente', // Prioridad opcional
                'estado' => 'nullable|string|in:Nuevo,Abierto,Asignado,En Proceso,Resuelto,Cerrado,Cancelado', // Estado opcional
                'idPoliticaSLA' => 'nullable|integer|exists:sla_politicas,idPoliticaSLA', // SLA opcional
            ]);

            // 3. Validación de rol - Verificar que el técnico existe, es técnico y está activo
            $tecnico = Empleado::where('idEmpleado', $data['idTecnico'])
                              ->where('rol', 'Tecnico')
                              ->where('estado', 'Activo')
                              ->first();
            
            if (!$tecnico) {
                return back()->withErrors(['idTecnico' => 'El técnico seleccionado no existe, no es técnico o no está activo.']);
            }

        // 3. Actualizar el reclamo
        $reclamo->idTecnicoAsignado = $data['idTecnico'];
        
        // Actualizar prioridad si se proporciona
        if (isset($data['prioridad'])) {
            $reclamo->prioridad = $data['prioridad'];
        }
        
        // Mantener el estado actual si no se proporciona uno nuevo
        if (isset($data['estado'])) {
            $reclamo->estado = $data['estado'];
        }
        
        // Actualizar SLA si se proporciona
        if (isset($data['idPoliticaSLA'])) {
            $reclamo->idPoliticaSLA = $data['idPoliticaSLA'];
        }
        
            $reclamo->save();

            // 4. Redirección final
            return redirect()->route('supervisor.tecnicos.dashboard')
                            ->with('success', 'Reclamo #' . $reclamo->idReclamo . ' reasignado correctamente a ' . $tecnico->primerNombre . '.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Errores de validación
            return back()->withErrors($e->errors())->withInput();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Modelo no encontrado
            return back()->withErrors(['error' => 'No se encontró el reclamo o el técnico especificado.'])->withInput();
        } catch (\Exception $e) {
            // Cualquier otro error
            \Log::error('Error al reasignar técnico: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al reasignar técnico: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Muestra el mapa con todos los reclamos y su ubicación geográfica
     */
    public function mapa()
    {
        // Obtener todos los reclamos con sus coordenadas y relaciones necesarias
        $reclamos = Reclamo::whereNotNull('latitudIncidente')
                          ->whereNotNull('longitudIncidente')
                          ->with(['usuario', 'tecnico', 'operador'])
                          ->orderBy('fechaCreacion', 'desc')
                          ->get();

        // Separar reclamos resueltos/cerrados de los pendientes
        $reclamosResueltos = $reclamos->filter(function($reclamo) {
            return in_array($reclamo->estado, ['Resuelto', 'Cerrado']);
        });

        $reclamosPendientes = $reclamos->filter(function($reclamo) {
            return !in_array($reclamo->estado, ['Resuelto', 'Cerrado']);
        });

        return view('supervisor_tecnicos.mapa_reclamos', [
            'reclamosResueltos' => $reclamosResueltos,
            'reclamosPendientes' => $reclamosPendientes,
            'supervisor' => Auth::guard('empleado')->user()
        ]);
    }
}