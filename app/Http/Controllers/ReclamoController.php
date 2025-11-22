<?php

namespace App\Http\Controllers;

use App\Models\Reclamo;
use App\Models\Usuario; // Asegúrate de que tu modelo se llame así
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ReclamoController extends Controller
{
    /**
     * Muestra el formulario (si tienes una vista separada, sino ignóralo)
     */
    public function index()
    {
        // Si quieres listar reclamos en JSON
        return response()->json(Reclamo::all());
    }

    // En App/Http/Controllers/ReclamoController.php

public function store(Request $request)
{
    // 1. Validamos solo lo que la base de datos soporta
    $request->validate([
        'titulo'               => 'required|string|max:255',
        'idTipoIncidente'      => 'required|integer', // Debe ser un ID válido
        'descripcionDetallada' => 'required|string',
        'prioridad'            => 'required|in:Baja,Media,Alta,Urgente',
        'latitudIncidente'     => 'required|numeric',
        'longitudIncidente'    => 'required|numeric',
    ]);

    // 2. Creamos el Reclamo
    $reclamo = new Reclamo();
    
    // Datos automáticos
    $reclamo->idUsuario = Auth::id(); // Como está dentro de middleware 'auth', esto siempre existe
    $reclamo->estado    = 'Nuevo';
    $reclamo->idPoliticaSLA = 1; // Valor por defecto (asegúrate de tener el ID 1 en la tabla sla_politicas)

    // Datos del Formulario
    $reclamo->titulo               = $request->titulo;
    $reclamo->idTipoIncidente      = $request->idTipoIncidente;
    $reclamo->descripcionDetallada = $request->descripcionDetallada;
    $reclamo->prioridad            = $request->prioridad;
    $reclamo->latitudIncidente     = $request->latitudIncidente;
    $reclamo->longitudIncidente    = $request->longitudIncidente;

    $reclamo->save();

    return back()->with('success', 'Reclamo registrado correctamente.');
}
}