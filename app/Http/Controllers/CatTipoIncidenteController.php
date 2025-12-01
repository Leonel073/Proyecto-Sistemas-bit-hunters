<?php

namespace App\Http\Controllers;

use App\Models\CatTipoIncidente;
use Illuminate\Http\Request;

class CatTipoIncidenteController extends Controller
{
    public function index()
    {
        return response()->json(CatTipoIncidente::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombreIncidente' => 'required|string|max:255|unique:cat_tipo_incidente,nombreIncidente',
            'descripcion' => 'nullable|string',
        ]);

        $tipo = CatTipoIncidente::create($request->all());

        return response()->json(['message' => 'Tipo de incidente creado', 'data' => $tipo]);
    }

    public function show($id)
    {
        return response()->json(CatTipoIncidente::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $tipo = CatTipoIncidente::findOrFail($id);
        $tipo->update($request->all());

        return response()->json(['message' => 'Tipo de incidente actualizado', 'data' => $tipo]);
    }

    public function destroy($id)
    {
        CatTipoIncidente::destroy($id);

        return response()->json(['message' => 'Tipo de incidente eliminado']);
    }
}
