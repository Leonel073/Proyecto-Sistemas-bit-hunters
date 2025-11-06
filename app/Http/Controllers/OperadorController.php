<?php

namespace App\Http\Controllers;

use App\Models\Operador;
use Illuminate\Http\Request;

class OperadorController extends Controller
{
    public function index()
    {
        return response()->json(Operador::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'idEmpleado' => 'required|integer|unique:operadores,idEmpleado',
            'turno' => 'required|string'
        ]);

        $operador = Operador::create($request->all());
        return response()->json(['message' => 'Operador creado', 'data' => $operador]);
    }

    public function show($id)
    {
        return response()->json(Operador::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $operador = Operador::findOrFail($id);
        $operador->update($request->all());
        return response()->json(['message' => 'Operador actualizado', 'data' => $operador]);
    }

    public function destroy($id)
    {
        Operador::destroy($id);
        return response()->json(['message' => 'Operador eliminado']);
    }
}
