<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

// Asegúrate de que el controlador base esté importado

class UsuarioController extends Controller
{
    // =========================================================
    // CRUD BÁSICO (Para la API o la administración de LISTA)
    // =========================================================
    public function index()
    {
        // Carga la lista de clientes/usuarios
        $usuarios = Usuario::whereNull('fechaEliminacion')->get();

        // ✅ CORRECCIÓN: Definimos $empleados como un array vacío para evitar el error
        $empleados = [];

        // Devolvemos ambas variables a la vista
        return view('gerente.usuarios.index', compact('usuarios', 'empleados'));
    }

    public function store(Request $request)
    {
        // Método usado para la creación de usuarios por API
        $request->validate([
            'primerNombre' => 'required|string|max:100',
            'apellidoPaterno' => 'required|string|max:100',
            'ci' => 'required|string|max:20|unique:usuarios,ci',
            'numeroCelular' => 'required|string|max:20|unique:usuarios,numeroCelular',
            'passwordHash' => 'required|string|min:8',
        ]);

        $usuario = Usuario::create($request->all());

        return response()->json(['message' => 'Usuario creado correctamente', 'data' => $usuario]);
    }

    public function show($id)
    {
        $usuario = Usuario::findOrFail($id);

        return response()->json($usuario);
    }

    // =========================================================
    // FUNCIONES DE ADMINISTRACIÓN (Rutas: admin.usuarios.*)
    // =========================================================

    // Muestra el formulario para editar (ADMIN)
    public function edit($id)
    {
        // $usuario = Usuario::findOrFail($id);
        // return view('views_usuarios.usuarios_edit', compact('usuario'));
        abort(404); // La edición de usuarios desde admin se ha deshabilitado en favor del bloqueo
    }

    // Procesa la actualización (ADMIN)
    // ✅ CORRECCIÓN DE REDIRECCIÓN APLICADA
    public function update(Request $request, $id)
    {
        try {
            // Validación de los campos que vienen del formulario de edición
            $request->validate([
                'primerNombre' => 'required|string|max:255',
                'apellidoPaterno' => 'required|string|max:255',
                // Validación de unicidad: ignora al usuario actual ($id)
                'email' => ['required', 'email', 'max:255', Rule::unique('usuarios')->ignore($id, 'idUsuario')],
                'numeroCelular' => ['required', 'string', 'max:20', Rule::unique('usuarios')->ignore($id, 'idUsuario')],
                'estado' => 'required|string',
            ]);

            $usuario = Usuario::findOrFail($id);

            // Asignación de valores
            $usuario->primerNombre = $request->primerNombre;
            $usuario->segundoNombre = $request->segundoNombre;
            $usuario->apellidoPaterno = $request->apellidoPaterno;
            $usuario->apellidoMaterno = $request->apellidoMaterno;
            $usuario->email = $request->email;
            $usuario->numeroCelular = $request->numeroCelular; // Agregado
            $usuario->estado = $request->estado;
            // CI no se actualiza por ser campo único

            $usuario->save();

            // ✅ REDIRECCIÓN CORREGIDA: Va a la lista principal del admin
            return redirect()->route('gerente.usuarios.index')->with('success', 'Usuario actualizado correctamente.');
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'Error al actualizar: '.$e->getMessage()]);
        }
    }

    // Borrado lógico (ADMIN)
    // ✅ CORRECCIÓN DE REDIRECCIÓN APLICADA
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update([
            'estado' => 'Eliminado',
            'fechaEliminacion' => now(),
        ]);

        // ✅ REDIRECCIÓN CORREGIDA: Va a la lista principal del admin
        return redirect()->route('gerente.usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }

    // Restaurar usuario (ADMIN)
    // ✅ CORRECCIÓN DE REDIRECCIÓN APLICADA
    public function restore($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update([
            'estado' => 'Activo',
            'fechaEliminacion' => null,
        ]);

        // ✅ REDIRECCIÓN CORREGIDA: Va a la lista de eliminados del admin
        return redirect()->route('gerente.usuarios.deleted')->with('success', 'Usuario reactivado correctamente.');
    }

    // Bloquear/Desbloquear usuario (ADMIN)
    public function toggleBlock($id)
    {
        $usuario = Usuario::findOrFail($id);
        
        if ($usuario->estado == 'Bloqueado') {
            $usuario->estado = 'Activo';
            $message = 'Usuario desbloqueado correctamente.';
        } else {
            $usuario->estado = 'Bloqueado';
            $message = 'Usuario bloqueado correctamente.';
        }
        
        $usuario->save();

        return redirect()->back()->with('success', $message);
    }

    // Ver usuarios eliminados (ADMIN)
    public function deleted()
    {
        $usuarios = Usuario::where('estado', 'Eliminado')->get();
        // Reutilizamos la vista de usuarios eliminados del gerente, pasando solo usuarios
        $empleados = collect([]); // ✅ CORREGIDO: Colección vacía para evitar error isEmpty()
        return view('gerente.usuarios.deleted', compact('usuarios', 'empleados'));
    }

    // =========================================================
    // FUNCIONES DE PERFIL DEL PROPIO USUARIO (CLIENTE)
    // =========================================================

    /**
     * Muestra el formulario para que el usuario edite sus propios datos
     */
    public function perfil()
    {
        $usuario = Auth::user();

        return view('cliente.profile', compact('usuario'));
    }

    /**
     * Procesa la actualización de datos del propio usuario
     */
    public function actualizarPerfil(Request $request)
    {
        $usuario = Auth::user();

        // Validación
        $request->validate([
            'primerNombre' => 'required|string|max:100',
            'segundoNombre' => 'nullable|string|max:100',
            'apellidoPaterno' => 'required|string|max:100',
            'apellidoMaterno' => 'nullable|string|max:100',
            'numeroCelular' => ['required', 'string', 'max:20', Rule::unique('usuarios')->ignore($usuario->idUsuario, 'idUsuario')],
            'email' => ['required', 'email', 'max:255', Rule::unique('usuarios')->ignore($usuario->idUsuario, 'idUsuario')],
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'numeroCelular.unique' => 'Este número de celular ya está en uso.',
            'email.unique' => 'Este correo electrónico ya está en uso.',
            'password.confirmed' => 'Las nuevas contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        // Actualizar datos básicos
        $usuario->primerNombre = $request->primerNombre;
        $usuario->segundoNombre = $request->segundoNombre;
        $usuario->apellidoPaterno = $request->apellidoPaterno;
        $usuario->apellidoMaterno = $request->apellidoMaterno;
        $usuario->numeroCelular = $request->numeroCelular;
        $usuario->email = $request->email;

        // Actualizar contraseña SOLO si el usuario escribió algo
        if ($request->filled('password')) {
            $usuario->passwordHash = Hash::make($request->password);
        }

        /** @var \App\Models\Usuario $usuario */
        $usuario->save();

        return redirect()->route('seguimiento')->with('success', 'Tu información ha sido actualizada correctamente.');
    }
}
