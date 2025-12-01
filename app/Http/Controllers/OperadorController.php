<?php

namespace App\Http\Controllers;

use App\Models\Operador;
use App\Models\Reclamo;
use App\Models\Tecnico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'turno' => 'required|string',
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

        // Obtener lista de técnicos disponibles para el modal
        $tecnicos = Tecnico::join('empleados', 'tecnicos.idEmpleado', '=', 'empleados.idEmpleado')
            ->where('empleados.estado', 'Activo')
            ->where('tecnicos.estadoDisponibilidad', 'Disponible') // Solo disponibles
            ->select([
                'tecnicos.idEmpleado',
                'empleados.primerNombre',
                'empleados.apellidoPaterno',
                'tecnicos.especialidad',
                'tecnicos.estadoDisponibilidad',
            ])
            ->get();

        // EXCEPCIÓN PARA SUPERADMIN (Testing):
        // Si el usuario actual es SuperAdmin, agregarlo a la lista manualmente para que pueda asignarse casos.
        if (Auth::guard('empleado')->user()->rol === 'SuperAdmin') {
            $superAdmin = Auth::guard('empleado')->user();
            // Creamos un objeto "fake" que cumpla con la estructura esperada
            $fakeTecnico = (object) [
                'idEmpleado' => $superAdmin->idEmpleado,
                'primerNombre' => $superAdmin->primerNombre,
                'apellidoPaterno' => $superAdmin->apellidoPaterno,
                'especialidad' => 'SuperAdmin (Test)',
                'estadoDisponibilidad' => 'Disponible'
            ];
            $tecnicos->push($fakeTecnico);
        }

        return view('operador.panel', compact('nuevos', 'misCasos', 'tecnicos'));
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
            ->map(function ($r) {
                // Si hay un técnico asignado, formatear su nombre completo
                $r->tecnicoNombre = $r->tecnico
                    ? $r->tecnico->primerNombre.' '.$r->tecnico->apellidoPaterno
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

        return back()->with('success', 'Reclamo asignado a su panel correctamente.');
    }

    /**
     * Asignar técnico y agregar comentario (desde modal).
     */
    public function asignarTecnico(Request $request, Reclamo $reclamo)
    {
        // 1. Validar datos
        $data = $request->validate([
            'idTecnico' => 'required|integer', 
            'prioridad' => 'required|string|in:Baja,Media,Alta,Urgente',
            'comentario' => 'required|string',
        ], [
            'idTecnico.required' => 'Debe seleccionar un técnico de la lista.',
            'idTecnico.integer' => 'El técnico seleccionado no es válido.',
            'prioridad.required' => 'Debe asignar una prioridad al caso.',
            'prioridad.in' => 'La prioridad seleccionada no es válida.',
            'comentario.required' => 'Es obligatorio agregar instrucciones o un comentario para el técnico.',
        ]);

        try {
            // 2. Preparar el comentario
            // Obtenemos los comentarios actuales (si existen)
            $comentarios = $reclamo->comentarios;

            // Asegurarnos de que sea un array (por si viene null o string)
            if (is_string($comentarios)) {
                $comentarios = json_decode($comentarios, true);
            }
            if (! is_array($comentarios)) {
                $comentarios = [];
            }

            $nuevoComentario = [
                'id' => now()->timestamp,
                'texto' => $data['comentario'],
                'autorId' => Auth::guard('empleado')->id(),
                'fecha' => now()->toDateTimeString(),
            ];

            // Añadir el nuevo comentario al array
            $comentarios[] = $nuevoComentario;

            // 3. Actualizar el reclamo
            $reclamo->comentarios = $comentarios;

            // ✅ CORRECCIÓN AQUÍ: Usamos el nombre real de la columna en la BD
            $reclamo->idTecnicoAsignado = $data['idTecnico'];
            $reclamo->prioridad = $data['prioridad']; // Actualizar prioridad asignada por el operador
            $reclamo->estado = 'Asignado';
            $reclamo->save();

            // 4. Actualizar estado del técnico a 'Ocupado'
            $tecnico = Tecnico::where('idEmpleado', $data['idTecnico'])->first();
            if ($tecnico) {
                $tecnico->estadoDisponibilidad = 'Ocupado';
                $tecnico->save();
            }

            return back()->with('success', 'Técnico asignado correctamente y marcado como Ocupado.');

        } catch (\Exception $e) {
            // Esto te ayudará a ver el error real si algo más falla
            return back()->withErrors(['error' => 'Error al asignar técnico: '.$e->getMessage()]);
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
                'tecnicos.estadoDisponibilidad',
            ])
            ->get();

        return response()->json($tecnicos);
    }
}
