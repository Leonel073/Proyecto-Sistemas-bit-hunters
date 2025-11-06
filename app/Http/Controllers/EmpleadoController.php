<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

// (Importa tus modelos de roles, ej: GerenteSoporte, Tecnico, etc.)
use App\Models\GerenteSoporte;
use App\Models\SupervisorOperador;
use App\Models\SupervisorTecnico;
use App\Models\Operador;
use App\Models\Tecnico;


class EmpleadoController extends Controller
{
    /**
     * Mostrar la lista de empleados activos (no eliminados)
     */
    public function index()
    {
        // NOTA: Esta vista se llama 'usuarios.blade.php' pero la ruta es 'admin.empleados.index'
        // ¡Esto funciona, pero es un poco confuso! Solo para que lo sepas.
        $empleados = Empleado::where('estado', '!=', 'Eliminado')->get();
        $usuarios  = Usuario::where('estado', '!=', 'Eliminado')->get();

        return view('usuarios', compact('empleados', 'usuarios'));
    }

    /**
     * Mostrar el formulario para crear un nuevo empleado
     */
    public function create()
    {
        // Verificar si ya existe un gerente de soporte
        $gerenteExiste = Empleado::where('rol', 'Gerente')->exists(); // Corregido 'gerente_soporte' a 'Gerente'
        return view('admin.empleados_create', compact('gerenteExiste'));
    }

    /**
     * Guardar un nuevo empleado
     */
    public function store(Request $request)
    {
        $request->validate([
            'primerNombre' => 'required|string|max:100',
            'segundoNombre' => 'nullable|string|max:100',
            'apellidoPaterno' => 'required|string|max:100',
            'apellidoMaterno' => 'nullable|string|max:100',
            'ci' => 'required|string|max:20|unique:empleados,ci',
            'numeroCelular' => 'nullable|string|max:20',
            'emailCorporativo' => 'required|email|max:150|unique:empleados,emailCorporativo',
            'password' => 'required|string|min:8',
            'rol' => 'required|in:Gerente,SupervisorOperador,SupervisorTecnico,Operador,Tecnico',
            'fechaIngreso' => 'required|date', // <-- Faltaba esta validación en tu original
        ]);


        // Si ya existe un gerente de soporte, evitar crear otro
        if ($request->rol === 'Gerente' && Empleado::where('rol', 'Gerente')->exists()) {
            return back()->withErrors(['rol' => 'Ya existe un Gerente de Soporte. No se puede crear otro.']);
        }


        $empleado = Empleado::create([
            'primerNombre' => $request->primerNombre,
            'segundoNombre' => $request->segundoNombre,
            'apellidoPaterno' => $request->apellidoPaterno,
            'apellidoMaterno' => $request->apellidoMaterno,
            'ci' => $request->ci,
            'numeroCelular' => $request->numeroCelular,
            'emailCorporativo' => $request->emailCorporativo,
            'passwordHash' => Hash::make($request->password),
            'rol' => $request->rol,
            'estado' => 'Activo',
            'fechaIngreso' => $request->fechaIngreso, // <-- Usar la fecha del request
        ]);

        // 👇 Insertar en tabla específica según el rol
        switch ($empleado->rol) {
            case 'Gerente':
                GerenteSoporte::create(['idEmpleado' => $empleado->idEmpleado]);
                break;
            case 'SupervisorOperador':
                SupervisorOperador::create(['idEmpleado' => $empleado->idEmpleado]);
                break;
            case 'SupervisorTecnico':
                SupervisorTecnico::create(['idEmpleado' => $empleado->idEmpleado]);
                break;
            case 'Operador':
                Operador::create(['idEmpleado' => $empleado->idEmpleado, 'turno' => 'Mañana']); // Valor por defecto
                break;
            case 'Tecnico':
                Tecnico::create(['idEmpleado' => $empleado->idEmpleado, 'especialidad' => 'General']); // Valor por defecto
                break;
        }

        // =================================================================
        // ¡¡AQUÍ ESTÁ EL PRIMER CAMBIO!! (Línea 99 en tu archivo)
        // =================================================================
        return redirect()->route('admin.empleados.index')->with('success', 'Empleado registrado correctamente.');
    }

    /**
     * Mostrar el formulario de edición de un empleado
     */
    public function edit(Empleado $empleado)
    {
        
        return view('admin.empleados_edit', [
            'empleado' => $empleado,
            // (Esta variable es por si no permitimos crear más de 1 gerente)
            'gerenteExiste' => Empleado::where('rol', 'Gerente')->exists()
        ]);
    }

    /**
     * Actualizar los datos de un empleado
     */
    public function update(Request $request, Empleado $empleado) // <-- Usar Route Model Binding
    {
        $request->validate([
            'primerNombre' => 'required|string|max:100',
            'apellidoPaterno' => 'required|string|max:100',
            'rol' => 'required|in:Gerente,SupervisorOperador,SupervisorTecnico,Operador,Tecnico',
            'fechaIngreso' => 'required|date', // <-- Añadido
            // (El email está deshabilitado en el form de edit, no hace falta validarlo)
        ]);

        // Separar los datos
        $datosEmpleado = $request->only([
            'primerNombre', 'segundoNombre', 'apellidoPaterno', 'apellidoMaterno',
            'ci', 'numeroCelular', 'rol', 'fechaIngreso'
        ]);

        // Solo actualizar contraseña SI se escribió una nueva
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $datosEmpleado['passwordHash'] = Hash::make($request->password);
        }

        $empleado->update($datosEmpleado);

        // =================================================================
        // ¡¡AQUÍ ESTÁ EL SEGUNDO CAMBIO!! (Línea 139 en tu archivo)
        // =================================================================
        return redirect()->route('admin.empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }

    /**
     * Eliminar (soft delete) un empleado
     */
    public function destroy(Empleado $empleado) // <-- Usar Route Model Binding
    {
        // No usamos 'Eliminado', usamos 'De Baja' como en tu BD de USUARIO
        $empleado->update([
            'estado' => 'Inactivo', // O 'De Baja' si lo prefieres
            'fechaEliminacion' => now(),
        ]);

        // =================================================================
        // ¡¡AQUÍ ESTÁ EL TERCER CAMBIO!! (Línea 153 en tu archivo)
        // =================================================================
        return redirect()->route('admin.empleados.index')->with('success', 'Empleado dado de baja correctamente.');
    }

    /**
     * Mostrar la lista de empleados eliminados
     */
    public function deleted()
    {
        // Buscamos los que no estén 'Activos'
        $empleados = Empleado::where('estado', '!=', 'Activo')->get();
        return view('admin.usuarios_deleted', compact('empleados'));
    }
}