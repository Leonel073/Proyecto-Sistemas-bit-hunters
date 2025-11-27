<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Operador;
use App\Models\SupervisorOperador; // Lo mantenemos por si lo usas en 'show'
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\Reclamo;
use Illuminate\Support\Facades\Auth;

class SupervisorOperadorController extends Controller
{
    // --- MÉTODOS PARA VISTAS BLADE ---

    /** Muestra el panel de gestión de Operadores */
    public function index()
    {
        $operadores = Empleado::with('operador')
            ->where('rol', 'Operador')
            ->where('estado', '!=', 'Eliminado')
            ->whereNull('fechaEliminacion')
            ->get();
        
        return view('supervisor_operadores.operadores', compact('operadores'));
    }

    /** Muestra el formulario para crear un nuevo Operador */
    public function create()
    {
        return view('supervisor_operadores.create');
    }

    /** Guarda el nuevo Operador en la BD */
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
            'rol' => 'Operador', // Rol predeterminado
            'estado' => 'Activo',
            'fechaIngreso' => $request->fechaIngreso,
        ]);

        Operador::create([
            'idEmpleado' => $empleado->idEmpleado,
            'turno' => $request->turno ?? 'Mañana' // Asigna turno (si lo añades al form) o usa 'Mañana'
        ]);

        return redirect()->route('supervisor.operadores.index')->with('success', 'Operador registrado correctamente.');
    }

    /** Muestra el formulario para editar un Operador */
    public function edit($id)
    {
        $empleado = Empleado::where('idEmpleado', $id)->where('rol', 'Operador')->firstOrFail();
        return view('supervisor_operadores.edit', compact('empleado'));
    }

    /** Actualiza un Operador */
    public function update(Request $request, $id)
    {
        $empleado = Empleado::where('idEmpleado', $id)->where('rol', 'Operador')->firstOrFail();

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

        return redirect()->route('supervisor.operadores.index')->with('success', 'Operador actualizado correctamente.');
    }

    /** Muestra la lista de Operadores eliminados */
    public function deleted()
    {
        $operadores = Empleado::where('rol', 'Operador')
                            ->where('estado', 'Eliminado')
                            ->get();
        return view('supervisor_operadores.deleted', compact('operadores'));
    }

    /** Restaura un Operador eliminado */
    public function restore($id)
    {
        $empleado = Empleado::where('idEmpleado', $id)->where('rol', 'Operador')->firstOrFail();
        $empleado->update([
            'estado' => 'Activo',
            'fechaEliminacion' => null,
        ]);

        return redirect()->route('supervisor.operadores.deleted')->with('success', 'Operador reactivado correctamente.');
    }

    /** Elimina (soft delete) un Operador */
    public function destroy($id)
    {
        $empleado = Empleado::where('idEmpleado', $id)->where('rol', 'Operador')->firstOrFail();
        $empleado->update([
            'estado' => 'Eliminado',
            'fechaEliminacion' => now(),
        ]);

        return redirect()->route('supervisor.operadores.index')->with('success', 'Operador eliminado correctamente.');
    }

    // --- MÉTODOS DE API JSON (Si aún los necesitas) ---

    /** Devuelve JSON de un supervisor específico (padre) */
    public function show($id)
    {
        // Esto busca en la tabla 'supervisores_operadores', no en 'empleados'
        return response()->json(SupervisorOperador::findOrFail($id));
    }
    // =========================================================
    // FUNCIONES DE REASIGNACIÓN (Dashboard)
    // =========================================================

    /**
     * Muestra el dashboard con los reclamos pendientes para reasignar.
     * (Función para la ruta supervisor.operadores.dashboard)
     */
    public function dashboard()
    {
        // 1. Reclamos que necesitan un operador (Nuevos o Abiertos)
        $reclamosPendientes = Reclamo::whereIn('estado', ['Nuevo', 'Abierto'])
                                ->with(['usuario', 'operador']) // Cargar relación de operador actual
                                ->orderBy('fechaCreacion', 'desc')
                                ->get();

        // 2. Lista de Operadores activos para el select (con relación operador para mostrar turno)
        $operadoresActivos = Empleado::where('rol', 'Operador')
                                    ->where('estado', 'Activo')
                                    ->with('operador') // Cargar relación para obtener turno
                                    ->orderBy('apellidoPaterno')
                                    ->get();

        // 3. Devolvemos la vista con los datos
        return view('supervisor_operadores.dashboard_reasignar', [
            'reclamos' => $reclamosPendientes,
            'operadores' => $operadoresActivos,
            // Obtenemos el usuario supervisor autenticado
            'supervisor' => Auth::guard('empleado')->user() 
        ]);
    }

    /**
     * Procesa la reasignación de un reclamo a un nuevo operador.
     * (Función para la ruta supervisor.operadores.reasignar)
     */
    public function reasignarOperador(Request $request, Reclamo $reclamo)
    {
        // 1. Validación
        $data = $request->validate([
            'idOperador' => 'required|integer|exists:empleados,idEmpleado', // ID del nuevo operador
            'prioridad' => 'nullable|string|in:Baja,Media,Alta,Urgente', // Prioridad opcional
            'estado' => 'nullable|string|in:Nuevo,Abierto,Asignado,En Proceso,Resuelto,Cerrado,Cancelado', // Estado opcional
            'idPoliticaSLA' => 'nullable|integer|exists:sla_politicas,idPoliticaSLA', // SLA opcional
        ]);

        // 2. Validación de rol
        $operador = Empleado::find($data['idOperador']);
        if ($operador->rol !== 'Operador') {
            return back()->withErrors('El ID proporcionado no corresponde a un Operador.');
        }

        // 3. Actualizar el reclamo
        $reclamo->idOperador = $data['idOperador'];
        
        // Actualizar prioridad si se proporciona
        if (isset($data['prioridad'])) {
            $reclamo->prioridad = $data['prioridad'];
        }
        
        // Actualizar estado si se proporciona, sino usar 'Asignado' por defecto
        $reclamo->estado = $data['estado'] ?? 'Asignado';
        
        // Actualizar SLA si se proporciona
        if (isset($data['idPoliticaSLA'])) {
            $reclamo->idPoliticaSLA = $data['idPoliticaSLA'];
        }
        
        $reclamo->save();

        // 4. Redirección final
        return redirect()->route('supervisor.operadores.dashboard')
                        ->with('success', 'Reclamo #' . $reclamo->idReclamo . ' reasignado correctamente a ' . $operador->primerNombre . '.');
    }
    public function actualizarGestion(Request $request, Reclamo $reclamo)
    {
        // 1. Validar que los campos de gestión sean correctos y obligatorios
        $request->validate([
            'prioridad' => ['required', Rule::in(['Baja', 'Media', 'Alta', 'Urgente'])], 
            'estado' => ['required', Rule::in(['Nuevo', 'Abierto', 'Asignado', 'En Proceso', 'Resuelto', 'Cerrado', 'Cancelado'])],
            'idPoliticaSLA' => 'required|integer|exists:sla_politicas,idPoliticaSLA', // Debe existir en la BD
        ]);

        try {
            // 2. Actualizar el reclamo con los nuevos valores de gestión
            $reclamo->update([
                'prioridad' => $request->prioridad,
                'estado' => $request->estado,
                'idPoliticaSLA' => $request->idPoliticaSLA,
            ]);

            return response()->json(['message' => 'Prioridad y gestión actualizadas correctamente.'], 200);

        } catch (\Exception $e) {
            // Devolver un error JSON si hay problemas de integridad de BD
            return response()->json(['message' => 'Error al actualizar gestión: ' . $e->getMessage()], 500);
        }
    }
}
