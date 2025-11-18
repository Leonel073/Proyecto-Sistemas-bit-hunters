<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password; // Importante: Añadir esto

class EmpleadoController extends Controller
{
    /**
     * Mostrar la lista de empleados activos (no eliminados)
     */
    public function index()
    {
        $empleados = Empleado::where('estado', '!=', 'Eliminado')
            ->whereNull('fechaEliminacion')
            ->get();
        $usuarios = Usuario::where('estado', '!=', 'Eliminado')
            ->whereNull('fechaEliminacion')
            ->get();

        return view('usuarios', compact('empleados', 'usuarios'));
    }

    /**
     * Mostrar el formulario para crear un nuevo empleado
     */
    public function create()
    {
        $gerenteExiste = Empleado::where('rol', 'Gerente')->exists(); // Corregido 'gerente_soporte' a 'Gerente'
        return view('admin.empleados_create', compact('gerenteExiste'));
    }

    /**
     * Guardar un nuevo empleado
     */
    public function store(Request $request)
    {
        // --- ✅ VALIDACIONES ESTRICTAS AÑADIDAS ✅ ---
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
            'rol' => 'required|in:Gerente,SupervisorOperador,SupervisorTecnico,Operador,Tecnico',
        ], [
            // ... (Mensajes de error en español) ...
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
            'fechaIngreso' => $request->fechaIngreso, // Usar la fecha del request
        ]);

        // 👇 Insertar en tabla específica según el rol
        switch ($empleado->rol) {
            case 'Gerente':
                \App\Models\GerenteSoporte::create(['idEmpleado' => $empleado->idEmpleado]);
                break;
            case 'SupervisorOperador':
                \App\Models\SupervisorOperador::create(['idEmpleado' => $empleado->idEmpleado]);
                break;
            case 'SupervisorTecnico':
                \App\Models\SupervisorTecnico::create(['idEmpleado' => $empleado->idEmpleado]);
                break;
            case 'Operador':
                \App\Models\Operador::create(['idEmpleado' => $empleado->idEmpleado, 'turno' => 'Mañana']);
                break;
            case 'Tecnico':
                \App\Models\Tecnico::create(['idEmpleado' => $empleado->idEmpleado, 'especialidad' => 'General']);
                break;
        }

        return redirect()->route('usuarios')->with('success', 'Empleado registrado correctamente.');
    }

    /**
     * Mostrar el formulario de edición de un empleado
     */
    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('admin.empleados_edit', compact('empleado'));
    }

    /**
     * Actualizar los datos de un empleado
     */
    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $request->validate([
            'primerNombre' => 'required|string|max:100',
            'apellidoPaterno' => 'required|string|max:100',
            'emailCorporativo' => [
                'required', 'email', 'max:150',
                Rule::unique('empleados')->ignore($empleado->idEmpleado, 'idEmpleado')
            ],
            'rol' => 'required|in:Gerente,SupervisorOperador,SupervisorTecnico,Operador,Tecnico',
            'estado' => 'required|string|in:Activo,Bloqueado,Eliminado',
        ]);

        $empleado->update([
            'primerNombre' => $request->primerNombre,
            'segundoNombre' => $request->segundoNombre,
            'apellidoPaterno' => $request->apellidoPaterno,
            'apellidoMaterno' => $request->apellidoMaterno,
            'emailCorporativo' => $request->emailCorporativo,
            'rol' => $request->rol,
            'estado' => $request->estado,
        ]);

        return redirect()->route('usuarios')->with('success', 'Empleado actualizado correctamente.');
    }

    /**
     * Eliminar (soft delete) un empleado
     */
    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->update([
            'estado' => 'Eliminado',
            'fechaEliminacion' => now(),
        ]);

        return redirect()->route('usuarios')->with('success', 'Empleado eliminado correctamente.');
    }

    /**
     * Mostrar la lista de empleados eliminados
     */
    public function deleted()
    {
        $empleados = Empleado::where('estado', 'Eliminado')->get();
        $usuarios = \App\Models\Usuario::where('estado', 'Eliminado')->get();

        return view('admin.usuarios_deleted', compact('empleados', 'usuarios'));
    }


    public function restore($id)
    {
        // En lugar de findOrFail, usamos find para manejar ambos tipos
        $empleado = Empleado::find($id);
        
        if ($empleado) {
             $empleado->update([
                'estado' => 'Activo',
                'fechaEliminacion' => null,
            ]);
            return redirect()->route('usuarios.deleted')->with('success', 'Empleado reactivado correctamente.');
        } 
        
        // Si no es empleado, busca en usuarios (basado en tu vista de eliminados)
        $usuario = Usuario::find($id);
         if ($usuario) {
            $usuario->update([
                'estado' => 'Activo',
                'fechaEliminacion' => null,
            ]);
            return redirect()->route('usuarios.deleted')->with('success', 'Usuario reactivado correctamente.');
        }

        return redirect()->route('usuarios.deleted')->withErrors('No se pudo encontrar el usuario o empleado.');
    }
}