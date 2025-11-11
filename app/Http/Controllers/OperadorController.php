<?php

namespace App\Http\Controllers;

use App\Models\Operador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reclamo;
use App\Models\Tecnico;
use Illuminate\Support\Facades\Validator;

class OperadorController extends Controller
{
    public function index()
    {
        return response()->json(Operador::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'idEmpleado' => 'required|integer|unique:operadores,idEmpleado',
            'turno' => 'required|string'
        ]);

        $operador = Operador::create($request->all());
        return response()->json(['message' => 'Operador creado', 'data' => $operador]);
    }

    public function show($id)
    {
        return response()->json(Operador::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $operador = Operador::findOrFail($id);
        $operador->update($request->all());
        return response()->json(['message' => 'Operador actualizado', 'data' => $operador]);
    }

    public function destroy($id)
    {
        Operador::destroy($id);
        return response()->json(['message' => 'Operador eliminado']);
    }

    /**
     * Panel para el Operador: muestra reclamos asignados con estado pendiente.
     */
    public function panel()
    {
        $empleadoId = Auth::guard('empleado')->id();

        // Reclamos nuevos sin operador (casos que requieren atención)
        $nuevos = Reclamo::whereNull('idOperador')
            ->where('estado', 'Nuevo')
            ->orderBy('fechaCreacion', 'desc')
            ->get();

        // Obtener reclamos asignados a este operador y que están pendientes
        $misCasos = Reclamo::where('idOperador', $empleadoId)
            ->whereIn('estado', ['Asignado', 'En Proceso'])
            ->orderBy('fechaCreacion', 'desc')
            ->get();

        return view('operador.panel', compact('nuevos', 'misCasos'));
    }

    // Devuelve reclamos nuevos (sin operador) en JSON
    public function nuevos()
    {
        $nuevos = Reclamo::whereNull('idOperador')
            ->where('estado', 'Nuevo')
            ->orderBy('fechaCreacion', 'desc')
            ->get();
        return response()->json($nuevos);
    }

    // Devuelve reclamos asignados al operador autenticado
    public function mis()
    {
        $empleadoId = Auth::guard('empleado')->id();
        $misCasos = Reclamo::where('idOperador', $empleadoId)
            ->whereIn('estado', ['Asignado', 'En Proceso', 'Abierto'])
            ->orderBy('fechaCreacion', 'desc')
            ->get();
        return response()->json($misCasos);
    }

    // El operador toma un caso (se asigna a sí mismo)
    public function tomar(Request $request, Reclamo $reclamo)
    {
        $empleadoId = Auth::guard('empleado')->id();
        if ($reclamo->idOperador) {
            return response()->json(['message' => 'El reclamo ya fue asignado'], 422);
        }
        $reclamo->idOperador = $empleadoId;
        $reclamo->estado = 'Abierto';
        $reclamo->save();
        return response()->json(['message' => 'Reclamo asignado al operador']);
    }

    // Asignar técnico y agregar comentario (desde modal)
    public function asignarTecnico(Request $request, Reclamo $reclamo)
    {
        $data = $request->validate([
            'idTecnico' => 'required|integer',
            'comentario' => 'required|string'
        ]);

        // Añadir comentario en campo JSON o tabla de seguimiento (aquí usamos campo 'comentarios' si existe)
        $comentarios = $reclamo->comentarios ?? [];
        $comentarios[] = [
            'id' => now()->timestamp,
            'texto' => $data['comentario'],
            'autorId' => Auth::guard('empleado')->id(),
            'fecha' => now()->toDateTimeString()
        ];
        $reclamo->comentarios = $comentarios;
        $reclamo->idTecnicoAsignado = $data['idTecnico'];
        $reclamo->estado = 'Asignado';
        $reclamo->save();

        return response()->json(['message' => 'Técnico asignado correctamente']);
    }

    // Obtener lista de técnicos disponibles
    public function tecnicos()
    {
        $tecnicos = Tecnico::join('empleados', 'tecnicos.idEmpleado', '=', 'empleados.idEmpleado')
            ->where('empleados.estado', 'Activo')
            ->get([
                'tecnicos.idEmpleado', 
                'empleados.primerNombre', 
                'empleados.apellidoPaterno',
                'tecnicos.especialidad', 
                'tecnicos.estadoDisponibilidad'
            ]);
        
        return response()->json($tecnicos);
    }
}
