<?php

namespace App\Http\Controllers;

use App\Models\RegistroAuditoria;
use Illuminate\Http\Request;

class RegistroAuditoriaController extends Controller
{
    public function index()
    {
        return response()->json(RegistroAuditoria::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'accion' => 'required|string|max:100',
            'detalleAccion' => 'nullable|string'
        ]);

        $registro = RegistroAuditoria::create($request->all());
        return response()->json(['message' => 'Registro de auditoría creado', 'data' => $registro]);
    }

    public function show($id)
    {
        return response()->json(RegistroAuditoria::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $registro = RegistroAuditoria::findOrFail($id);
        $registro->update($request->all());
        return response()->json(['message' => 'Registro de auditoría actualizado', 'data' => $registro]);
    }

    public function destroy($id)
    {
        RegistroAuditoria::destroy($id);
        return response()->json(['message' => 'Registro de auditoría eliminado']);
    }
}
