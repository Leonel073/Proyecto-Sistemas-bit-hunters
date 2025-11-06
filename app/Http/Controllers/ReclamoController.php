<?php

namespace App\Http\Controllers;

use App\Models\Reclamo;
use Illuminate\Http\Request;

class ReclamoController extends Controller
{
    public function index()
    {
        return response()->json(Reclamo::whereNull('fechaEliminacion')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'idUsuario' => 'required|integer',
            'idPoliticaSLA' => 'required|integer',
            'idTipoIncidente' => 'required|integer',
            'titulo' => 'required|string|max:255',
            'descripcionDetallada' => 'required|string',
            'prioridad' => 'required|string'
        ]);

        $reclamo = Reclamo::create($request->all());
        return response()->json(['message' => 'Reclamo registrado correctamente', 'data' => $reclamo]);
    }

    public function show($id)
    {
        return response()->json(Reclamo::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $reclamo = Reclamo::findOrFail($id);
        $reclamo->update($request->all());
        return response()->json(['message' => 'Reclamo actualizado', 'data' => $reclamo]);
    }

    public function destroy($id)
    {
        $reclamo = Reclamo::findOrFail($id);
        $reclamo->update(['fechaEliminacion' => now()]);
        return response()->json(['message' => 'Reclamo eliminado (soft delete)']);
    }
}
