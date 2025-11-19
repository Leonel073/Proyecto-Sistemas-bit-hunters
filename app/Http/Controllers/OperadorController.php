<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Operador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reclamo;
use App\Models\Tecnico;

class OperadorController extends Controller
{
    // --- MÉTODOS CRUD BÁSICOS (API) ---

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

    // --- MÉTODOS DE FLUJO DE TRABAJO (PANEL) ---

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
            ->whereIn('estado', ['Asignado', 'En Proceso', 'Abierto'])
            ->orderBy('fechaCreacion', 'desc')
            ->get();

        return view('operador.panel', compact('nuevos', 'misCasos'));
    }

    /**
     * Devuelve reclamos nuevos (sin operador) en JSON.
     */
    public function nuevos()
    {
        $nuevos = Reclamo::whereNull('idOperador')
            ->where('estado', 'Nuevo')
            ->with('usuario')
            ->orderBy('fechaCreacion', 'desc')
            ->get();
        return response()->json($nuevos);
    }

    /**
     * Devuelve reclamos asignados al operador autenticado.
     */
    public function mis()
    {
        $empleadoId = Auth::guard('empleado')->id();
        $misCasos = Reclamo::where('idOperador', $empleadoId)
            ->whereIn('estado', ['Asignado', 'En Proceso', 'Abierto'])
            ->with(['usuario', 'tecnico']) // Asegúrate de que la relación 'tecnico' exista en el modelo Reclamo
            ->orderBy('fechaCreacion', 'desc')
            ->get()
            ->map(function($r) {
                // Si hay un técnico asignado, formatear su nombre completo
                $r->tecnicoNombre = $r->tecnico 
                    ? $r->tecnico->primerNombre . ' ' . $r->tecnico->apellidoPaterno 
                    : 'Sin asignar';
                return $r;
            });
        return response()->json($misCasos);
    }

    /**
     * El operador toma un caso (se asigna a sí mismo).
     */
    public function tomar(Request $request, Reclamo $reclamo)
    {
        $empleadoId = Auth::guard('empleado')->id();
        
        if ($reclamo->idOperador) {
            return response()->json(['message' => 'El reclamo ya fue asignado'], 422);
        }

        $reclamo->idOperador = $empleadoId;
        $reclamo->estado = 'Abierto'; // O el estado que corresponda al tomarlo
        $reclamo->save();

        return response()->json(['message' => 'Reclamo asignado al operador']);
    }

    /**
     * Asignar técnico y agregar comentario (desde modal).
     */
    public function asignarTecnico(Request $request, Reclamo $reclamo)
    {
        // 1. Validar datos
        $data = $request->validate([
            'idTecnico' => 'required|integer',
            'comentario' => 'required|string'
        ]);

        try {
            // 2. Preparar el comentario (Asumiendo que 'comentarios' es un campo JSON en la BD)
            // Si es una tabla aparte, deberías usar ReclamoComentario::create(...)
            $comentarios = $reclamo->comentarios ?? [];
            
            $nuevoComentario = [
                'id' => now()->timestamp,
                'texto' => $data['comentario'],
                'autorId' => Auth::guard('empleado')->id(),
                'fecha' => now()->toDateTimeString()
            ];

            // Si es JSON, decodificar si viene como string, o añadir al array
            if (is_string($comentarios)) {
                $comentarios = json_decode($comentarios, true) ?? [];
            }
            $comentarios[] = $nuevoComentario;

            // 3. Actualizar el reclamo
            $reclamo->comentarios = $comentarios; // Laravel se encarga de convertir a JSON si está casteado en el modelo
            $reclamo->idTecnico = $data['idTecnico']; // Asegúrate que el campo en BD sea idTecnico o idTecnicoAsignado
            $reclamo->estado = 'Asignado'; // Cambiar estado
            $reclamo->save();

            return response()->json(['message' => 'Técnico asignado correctamente']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al asignar técnico: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Obtener lista de técnicos disponibles.
     */
    public function tecnicos()
    {
        // Unimos la tabla tecnicos con empleados para obtener el nombre
        $tecnicos = Tecnico::join('empleados', 'tecnicos.idEmpleado', '=', 'empleados.idEmpleado')
            ->where('empleados.estado', 'Activo')
            ->select([
                'tecnicos.idEmpleado', 
                'empleados.primerNombre', 
                'empleados.apellidoPaterno',
                'tecnicos.especialidad', 
                'tecnicos.estadoDisponibilidad'
            ])
            ->get();
        
        return response()->json($tecnicos);
    }
}