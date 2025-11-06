<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

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

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update($request->all());
        return response()->json(['message' => 'Usuario actualizado correctamente', 'data' => $usuario]);
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update(['fechaEliminacion' => now()]);
        return response()->json(['message' => 'Usuario eliminado (soft delete)']);
    }
}
