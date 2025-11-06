<?php

namespace App\Http\Controllers;

use App\Models\SlaPolitica;
use Illuminate\Http\Request;

class SlaPoliticaController extends Controller
{
    public function index()
    {
        return response()->json(SlaPolitica::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombrePolitica' => 'required|string|max:255',
            'prioridad' => 'required|string',
            'tiempoMaxSolucionHoras' => 'required|integer|min:1'
        ]);

        $sla = SlaPolitica::create($request->all());
        return response()->json(['message' => 'Política SLA creada correctamente', 'data' => $sla]);
    }

    public function show($id)
    {
        return response()->json(SlaPolitica::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $sla = SlaPolitica::findOrFail($id);
        $sla->update($request->all());
        return response()->json(['message' => 'Política SLA actualizada', 'data' => $sla]);
    }

    public function destroy($id)
    {
        SlaPolitica::destroy($id);
        return response()->json(['message' => 'Política SLA eliminada']);
    }
}
