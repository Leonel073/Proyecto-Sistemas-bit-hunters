<?php

namespace App\Http\Controllers;

use App\Models\SupervisorTecnico;
use Illuminate\Http\Request;

class SupervisorTecnicoController extends Controller
{
    public function index()
    {
        return response()->json(SupervisorTecnico::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'idEmpleado' => 'required|integer|unique:supervisores_tecnicos,idEmpleado'
        ]);

        $supervisor = SupervisorTecnico::create($request->all());
        return response()->json(['message' => 'Supervisor Técnico creado', 'data' => $supervisor]);
    }

    public function show($id)
    {
        return response()->json(SupervisorTecnico::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $supervisor = SupervisorTecnico::findOrFail($id);
        $supervisor->update($request->all());
        return response()->json(['message' => 'Supervisor Técnico actualizado', 'data' => $supervisor]);
    }

    public function destroy($id)
    {
        SupervisorTecnico::destroy($id);
        return response()->json(['message' => 'Supervisor Técnico eliminado']);
    }
}
