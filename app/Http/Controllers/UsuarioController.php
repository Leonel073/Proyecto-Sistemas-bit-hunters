<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('views_usuarios.usuarios_edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Validar datos
            $request->validate([
                'primerNombre' => 'required|string|max:255',
                'apellidoPaterno' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'estado' => 'required|string',
            ]);

            // Buscar usuario
            $usuario = Usuario::findOrFail($id);

            // Actualizar campos
            $usuario->primerNombre = $request->primerNombre;
            $usuario->segundoNombre = $request->segundoNombre;
            $usuario->apellidoPaterno = $request->apellidoPaterno;
            $usuario->apellidoMaterno = $request->apellidoMaterno;
            $usuario->email = $request->email;
            $usuario->estado = $request->estado;

            // Guardar cambios
            $usuario->save();

            // Redirigir con mensaje
            return redirect()->route('usuarios')->with('success', 'Usuario actualizado correctamente.');
        } catch (\Throwable $e) {
            // Si ocurre un error, mostrarlo temporalmente
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

}