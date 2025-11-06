<?php

namespace App\Http\Controllers;

use App\Models\GerenteSoporte;
use Illuminate\Http\Request;

class GerenteSoporteController extends Controller
{
    public function index()
    {
        return response()->json(GerenteSoporte::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'idEmpleado' => 'required|integer|unique:gerentes_soporte,idEmpleado'
        ]);

        $gerente = GerenteSoporte::create($request->all());
        return response()->json(['message' => 'Gerente registrado', 'data' => $gerente]);
    }

    public function show($id)
    {
        return response()->json(GerenteSoporte::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $gerente = GerenteSoporte::findOrFail($id);
        $gerente->update($request->all());
        return response()->json(['message' => 'Gerente actualizado', 'data' => $gerente]);
    }

    public function destroy($id)
    {
        GerenteSoporte::destroy($id);
        return response()->json(['message' => 'Gerente eliminado']);
    }
}
