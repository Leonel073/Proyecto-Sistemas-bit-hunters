<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // Importante
use Illuminate\Support\Facades\Hash; // Importante para la contraseña

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::whereNull('fechaEliminacion')->get();
        return response()->json($usuarios);
    }

    public function store(Request $request)
    {
        $request->validate([
            'primerNombre' => 'required|string|max:100',
            'apellidoPaterno' => 'required|string|max:100',
            'ci' => 'required|string|max:20|unique:usuarios,ci',
            'numeroCelular' => 'required|string|max:20|unique:usuarios,numeroCelular',
            'passwordHash' => 'required|string|min:8'
        ]);

        $usuario = Usuario::create($request->all());
        return response()->json(['message' => 'Usuario creado correctamente', 'data' => $usuario]);
    }

    public function show($id)
    {
        $usuario = Usuario::findOrFail($id);
        return response()->json($usuario);
    }

    // Esta función es para que el ADMIN edite a un usuario
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('views_usuarios.usuarios_edit', compact('usuario'));
    }

    // Esta función es para que el ADMIN actualice a un usuario
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'primerNombre' => 'required|string|max:255',
                'apellidoPaterno' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'estado' => 'required|string',
            ]);

            $usuario = Usuario::findOrFail($id);

            $usuario->primerNombre = $request->primerNombre;
            $usuario->segundoNombre = $request->segundoNombre;
            $usuario->apellidoPaterno = $request->apellidoPaterno;
            $usuario->apellidoMaterno = $request->apellidoMaterno;
            $usuario->email = $request->email;
            $usuario->estado = $request->estado;

            $usuario->save();

            return redirect()->route('usuarios')->with('success', 'Usuario actualizado correctamente.');
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'Error al actualizar: '.$e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update([
            'estado' => 'Eliminado',
            'fechaEliminacion' => now(),
        ]);

        return redirect()->route('usuarios')->with('success', 'Usuario eliminado correctamente.');
    }

    public function restore($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update([
            'estado' => 'Activo',
            'fechaEliminacion' => null,
        ]);

        return redirect()->route('usuarios.deleted')->with('success', 'Usuario reactivado correctamente.');
    }

    // ==========================================
    // ✅ NUEVAS FUNCIONES PARA EL PERFIL DEL USUARIO
    // ==========================================

    /**
     * Muestra el formulario para que el usuario edite sus propios datos
     */
    public function perfil()
    {
        // Obtener el usuario autenticado actualmente
        $usuario = Auth::user();
        return view('views_usuarios/perfil_editar', compact('usuario'));
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
            // La contraseña es opcional (nullable)
            'password' => 'nullable|string|min:8|confirmed', 
        ], [
            'numeroCelular.unique' => 'Este número de celular ya está en uso.',
            'email.unique' => 'Este correo electrónico ya está en uso.',
            'password.confirmed' => 'Las nuevas contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.'
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

        return redirect()->route('perfil.editar')->with('success', 'Tu información ha sido actualizada correctamente.');
    }
}