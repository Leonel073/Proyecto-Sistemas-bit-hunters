<?php

namespace App\Http\Controllers;

use App\Models\SupervisorOperador;
use Illuminate\Http\Request;

class SupervisorOperadorController extends Controller
{
    public function index()
    {
        return response()->json(SupervisorOperador::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'idEmpleado' => 'required|integer|unique:supervisores_operadores,idEmpleado'
        ]);

        $supervisor = SupervisorOperador::create($request->all());
        return response()->json(['message' => 'Supervisor registrado', 'data' => $supervisor]);
    }

    public function show($id)
    {
        return response()->json(SupervisorOperador::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $supervisor = SupervisorOperador::findOrFail($id);
        $supervisor->update($request->all());
        return response()->json(['message' => 'Supervisor actualizado', 'data' => $supervisor]);
    }

    public function destroy($id)
    {
        SupervisorOperador::destroy($id);
        return response()->json(['message' => 'Supervisor eliminado']);
    }
}
