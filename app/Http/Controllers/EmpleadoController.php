<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::where('estado', '!=', 'Eliminado')
            ->whereNull('fechaEliminacion')
            ->orderBy('apellidoPaterno')
            ->get();

        return view('gerente.empleados', compact('empleados'));
    }

    public function create()
    {
        $gerenteExiste = Empleado::where('rol', 'Gerente')->exists();

        return view('gerente.empleados_create', compact('gerenteExiste'));
    }

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
            'rol' => 'required|in:Gerente,SupervisorOperador,SupervisorTecnico,Operador,Tecnico',
        ]);

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
            'fechaIngreso' => $request->fechaIngreso,
        ]);

        switch ($empleado->rol) {
            case 'Gerente': \App\Models\GerenteSoporte::create(['idEmpleado' => $empleado->idEmpleado]);
                break;
            case 'SupervisorOperador': \App\Models\SupervisorOperador::create(['idEmpleado' => $empleado->idEmpleado]);
                break;
            case 'SupervisorTecnico': \App\Models\SupervisorTecnico::create(['idEmpleado' => $empleado->idEmpleado]);
                break;
            case 'Operador': \App\Models\Operador::create(['idEmpleado' => $empleado->idEmpleado, 'turno' => 'Mañana']);
                break;
            case 'Tecnico': \App\Models\Tecnico::create(['idEmpleado' => $empleado->idEmpleado, 'especialidad' => 'General']);
                break;
        }

        // ✅ CORREGIDO: gerente.empleados.index
        return redirect()->route('gerente.empleados.index')->with('success', 'Empleado registrado correctamente.');
    }

    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);

        return view('gerente.empleados_edit', compact('empleado'));
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $request->validate([
            'primerNombre' => 'required|string|max:100',
            'apellidoPaterno' => 'required|string|max:100',
            'emailCorporativo' => ['required', 'email', 'max:150', Rule::unique('empleados')->ignore($empleado->idEmpleado, 'idEmpleado')],
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

        // ✅ CORREGIDO: gerente.empleados.index
        return redirect()->route('gerente.empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->update(['estado' => 'Eliminado', 'fechaEliminacion' => now()]);

        // ✅ CORREGIDO: gerente.empleados.index
        return redirect()->route('gerente.empleados.index')->with('success', 'Empleado eliminado correctamente.');
    }

    public function deleted()
    {
        $empleados = Empleado::where('estado', 'Eliminado')->get();
        $usuarios = \App\Models\Usuario::where('estado', 'Eliminado')->get();

        return view('gerente.usuarios_deleted', compact('empleados', 'usuarios'));
    }

    public function restore($id)
    {
        $empleado = Empleado::find($id);
        if ($empleado) {
            $empleado->update(['estado' => 'Activo', 'fechaEliminacion' => null]);

            // ✅ CORREGIDO: gerente.empleados.deleted
            return redirect()->route('gerente.empleados.deleted')->with('success', 'Empleado reactivado correctamente.');
        }

        $usuario = Usuario::find($id);
        if ($usuario) {
            $usuario->update(['estado' => 'Activo', 'fechaEliminacion' => null]);

            // ✅ CORREGIDO: gerente.empleados.deleted
            return redirect()->route('gerente.empleados.deleted')->with('success', 'Usuario reactivado correctamente.');
        }

        return redirect()->route('admin.empleados.deleted')->withErrors('No se pudo encontrar el usuario o empleado.');
    }
}
