<?php

namespace App\Http\Controllers;

use App\Models\Tecnico;
use Illuminate\Http\Request;

class TecnicoController extends Controller
{
    public function index()
    {
        return response()->json(Tecnico::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'idEmpleado' => 'required|integer|unique:tecnicos,idEmpleado',
            'especialidad' => 'required|string|max:100',
            'estadoDisponibilidad' => 'nullable|string'
        ]);

        $tecnico = Tecnico::create($request->all());
        return response()->json(['message' => 'Técnico registrado correctamente', 'data' => $tecnico]);
    }

    public function show($id)
    {
        return response()->json(Tecnico::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $tecnico = Tecnico::findOrFail($id);
        $tecnico->update($request->all());
        return response()->json(['message' => 'Técnico actualizado', 'data' => $tecnico]);
    }

    public function destroy($id)
    {
        Tecnico::destroy($id);
        return response()->json(['message' => 'Técnico eliminado']);
    }
}
