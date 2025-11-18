<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Tecnico;
use App\Models\SupervisorTecnico; // Lo mantenemos por si lo usas en 'show'
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
}