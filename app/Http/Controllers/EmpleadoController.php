<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmpleadoController extends Controller
{
    /**
     * Mostrar la lista de empleados activos (no eliminados)
     */
    public function index()
    {
        // Solo empleados activos (no eliminados lógicamente)
        $empleados = Empleado::where('estado', '!=', 'Eliminado')
            ->whereNull('fechaEliminacion')
            ->get();

        // Solo usuarios activos (no eliminados lógicamente)
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
        // Verificar si ya existe un gerente de soporte
        $gerenteExiste = Empleado::where('rol', 'gerente_soporte')->exists();
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
        'fechaIngreso' => now(),
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
        $usuarios  = \App\Models\Usuario::where('estado', 'Eliminado')->get();

        return view('admin.usuarios_deleted', compact('empleados', 'usuarios'));
    }


    public function restore($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->update([
            'estado' => 'Activo',
            'fechaEliminacion' => null,
        ]);

        return redirect()->route('usuarios.deleted')->with('success', 'Empleado reactivado correctamente.');
    }

}