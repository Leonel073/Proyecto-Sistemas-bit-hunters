<?php

namespace App\Http\Controllers;

use App\Models\Reclamo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CatTipoIncidente;
use App\Models\SlaPolitica;
use App\Models\Operador;

class ReclamoController extends Controller
{
    // Listar reclamos activos (no eliminados)
    public function index()
    {
        $reclamos = Reclamo::whereNull('fechaEliminacion')
            ->orderBy('fechaCreacion', 'desc')
            ->get();

        return response()->json($reclamos);
    }

    /**
     * Procesa el formulario enviado desde la interfaz (web) y crea un Reclamo.
     * Mapea campos simples (tipoIncidente por nombre) a las tablas necesarias.
     */
    public function storeFront(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcionDetallada' => 'required|string',
            'tipoIncidente' => 'required|string',
            'velocidadContratada' => 'nullable|numeric',
            'velocidadReal' => 'nullable|numeric',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
        ]);

        $user = $request->user();

        // Buscar o crear el tipo de incidente por nombre (si no existe)
        $tipo = CatTipoIncidente::firstOrCreate([
            'nombreIncidente' => $request->input('tipoIncidente')
        ]);

        // Seleccionar una política SLA por defecto (la primera disponible)
        $sla = SlaPolitica::first();

        // Intentamos asignar automáticamente un Operador disponible.
        $operadorAsignadoId = null;
        $operadores = Operador::all();
        if ($operadores->isNotEmpty()) {
            // Calcular el número de reclamos pendientes por operador y elegir el de menos carga
            $min = null;
            foreach ($operadores as $op) {
                $count = Reclamo::where('idOperador', $op->idEmpleado)
                    ->whereIn('estado', ['Nuevo', 'Asignado', 'En Proceso'])
                    ->count();
                if ($min === null || $count < $min['count']) {
                    $min = ['id' => $op->idEmpleado, 'count' => $count];
                }
            }
            $operadorAsignadoId = $min['id'] ?? null;
        }

        // Crear el reclamo con mapeo básico. Se usan valores por defecto cuando faltan campos.
        $reclamo = Reclamo::create([
            'idUsuario' => $user->idUsuario ?? $user->id ?? null,
            'idOperador' => $operadorAsignadoId,
            'idTecnicoAsignado' => null,
            'idPoliticaSLA' => $sla->idPoliticaSLA ?? 1,
            'idTipoIncidente' => $tipo->idTipoIncidente,
            'idCausaRaiz' => null,
            'titulo' => $request->input('titulo'),
            'descripcionDetallada' => $request->input('descripcionDetallada'),
            'solucionTecnica' => null,
            'estado' => $operadorAsignadoId ? 'Asignado' : 'Nuevo',
            'prioridad' => 'Media',
            'latitudIncidente' => $request->input('latitud', 0.0),
            'longitudIncidente' => $request->input('longitud', 0.0),
        ]);

        return redirect()->route('formulario')->with('success', 'El reclamo R-' . $reclamo->idReclamo . ' se registró correctamente.');
    }

    // Crear reclamo nuevo
    public function store(Request $request)
    {
        $request->validate([
            'idUsuario' => 'required|integer|exists:USUARIO,idUsuario',
            'idPoliticaSLA' => 'required|integer|exists:SLA_POLITICA,idPoliticaSLA',
            'idTipoIncidente' => 'required|integer|exists:CAT_TIPO_INCIDENTE,idTipoIncidente',
            'titulo' => 'required|string|max:255',
            'descripcionDetallada' => 'required|string',
            'prioridad' => 'required|in:Baja,Media,Alta,Urgente',
            'latitudIncidente' => 'required|numeric',
            'longitudIncidente' => 'required|numeric'
        ]);

        $reclamo = Reclamo::create(array_merge(
            $request->all(),
            ['estado' => 'Nuevo'] // 👈 estado inicial
        ));

        return response()->json([
            'message' => 'Reclamo registrado correctamente',
            'data' => $reclamo
        ], 201);
    }

    // Mostrar reclamo por ID
    public function show($id)
    {
        $reclamo = Reclamo::findOrFail($id);
        return response()->json($reclamo);
    }

    // Actualizar información del reclamo
    public function update(Request $request, $id)
    {
        $reclamo = Reclamo::findOrFail($id);
        $reclamo->update($request->all());

        return response()->json([
            'message' => 'Reclamo actualizado correctamente',
            'data' => $reclamo
        ]);
    }

    // Borrado lógico
    public function destroy($id)
    {
        $reclamo = Reclamo::findOrFail($id);
        $reclamo->update(['fechaEliminacion' => now()]);

        return response()->json(['message' => 'Reclamo eliminado (soft delete)']);
    }

    // ✅ Panel de reclamos pendientes (para el operador)
    public function pendientes()
    {
        $reclamos = Reclamo::whereNull('fechaEliminacion')
            ->whereIn('estado', ['Nuevo', 'Abierto', 'Asignado'])
            ->get();

        return response()->json($reclamos);
    }
}
