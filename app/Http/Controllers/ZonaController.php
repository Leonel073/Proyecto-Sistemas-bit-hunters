<?php

namespace App\Http\Controllers;

use App\Models\Zona;
use Illuminate\Http\Request;

class ZonaController extends Controller
{
    public function index()
    {
        $zonas = Zona::orderBy('nombreZona')->get();
        return view('admin.zonas', compact('zonas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombreZona' => 'required|string|max:100|unique:zonas,nombreZona',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        Zona::create($request->all());

        return redirect()->route('admin.zonas')->with('success', 'Zona creada correctamente');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombreZona' => 'required|string|max:100|unique:zonas,nombreZona,' . $id . ',idZona',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        $zona = Zona::findOrFail($id);
        $zona->update($request->all());

        return redirect()->route('admin.zonas')->with('success', 'Zona actualizada correctamente');
    }

    public function destroy($id)
    {
        $zona = Zona::findOrFail($id);
        $zona->delete();

        return redirect()->route('admin.zonas')->with('success', 'Zona eliminada correctamente');
    }
}


