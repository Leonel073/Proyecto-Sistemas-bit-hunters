<?php

namespace App\Http\Controllers;

use App\Models\CatCausaRaiz;
use Illuminate\Http\Request;

class CatCausaRaizController extends Controller
{
    public function index()
    {
        return response()->json(CatCausaRaiz::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombreCausa' => 'required|string|max:255|unique:cat_causa_raiz,nombreCausa',
            'descripcion' => 'nullable|string',
        ]);

        $causa = CatCausaRaiz::create($request->all());

        return response()->json(['message' => 'Causa raíz registrada', 'data' => $causa]);
    }

    public function show($id)
    {
        return response()->json(CatCausaRaiz::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $causa = CatCausaRaiz::findOrFail($id);
        $causa->update($request->all());

        return response()->json(['message' => 'Causa raíz actualizada', 'data' => $causa]);
    }

    public function destroy($id)
    {
        CatCausaRaiz::destroy($id);

        return response()->json(['message' => 'Causa raíz eliminada']);
    }
}
