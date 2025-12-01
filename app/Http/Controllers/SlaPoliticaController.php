<?php

namespace App\Http\Controllers;

use App\Models\SlaPolitica;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // <-- Importar Rule para validación única

class SlaPoliticaController extends Controller
{
    // Definimos las prioridades como propiedad de la clase para usarlas en las validaciones
    private $prioridades = ['Urgente', 'Alta', 'Media', 'Baja'];

    public function index()
    {
        // Se sugiere usar la propiedad aquí, aunque no es estrictamente necesario
        $politicas = SlaPolitica::orderByRaw("FIELD(prioridad, 'Urgente', 'Alta', 'Media', 'Baja')")->get();
        
        // Pasamos las prioridades disponibles a la vista para el formulario
        $prioridadesDisponibles = $this->prioridades; 
        
        // Hemos asumido que la ruta de la vista es 'gerente.sla-politicas' para la HU-12
        return view('gerente.sla_politicas', compact('politicas', 'prioridadesDisponibles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombrePolitica' => 'required|string|max:255',
            // CORRECCIÓN 1 & 2: Incluimos 'Urgente' y la regla 'unique' para asegurar una sola política por prioridad.
            'prioridad' => [
                'required', 
                'string', 
                Rule::in($this->prioridades),
                'unique:sla_politicas,prioridad' // ¡CRÍTICO! Asegura que no exista otra política con esta prioridad.
            ],
            'tiempoMaxSolucionHoras' => 'required|integer|min:1|max:720',
            // CORRECCIÓN 3: Validación explícita de valor booleano (0 o 1)
            'estaActiva' => 'nullable|numeric|in:0,1',
        ]);

        $data = $request->all();
        // El checkbox en el formulario solo envía valor si está marcado. 
        // Si usas un campo oculto, puedes simplificar esto a: $data['estaActiva'] = $request->has('estaActiva');
        $data['estaActiva'] = $request->has('estaActiva') ? 1 : 0;

        SlaPolitica::create($data);

        return redirect()->route('gerente.sla-politicas')->with('success', 'Política SLA creada correctamente');
    }

    public function show($id)
    {
        return response()->json(SlaPolitica::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $sla = SlaPolitica::findOrFail($id);

        $request->validate([
            'nombrePolitica' => 'required|string|max:255',
            // CORRECCIÓN 2 (UPDATE): La prioridad debe ser única EXCEPTO para el registro que estamos actualizando.
            'prioridad' => [
                'required', 
                'string', 
                Rule::in($this->prioridades),
                Rule::unique('sla_politicas', 'prioridad')->ignore($sla->idPoliticaSLA, 'idPoliticaSLA') // <-- CRÍTICO
            ],
            'tiempoMaxSolucionHoras' => 'required|integer|min:1|max:720',
            // CORRECCIÓN 3
            'estaActiva' => 'nullable|numeric|in:0,1',
        ]);

        $data = $request->all();
        $data['estaActiva'] = $request->has('estaActiva') ? 1 : 0;
        $sla->update($data);

        return redirect()->route('gerente.sla-politicas')->with('success', 'Política SLA actualizada correctamente');
    }

    public function destroy($id)
    {
        $sla = SlaPolitica::findOrFail($id);
        
        // ***********************************************************************************
        // ⚠️ ADVERTENCIA: Se recomienda encarecidamente verificar si hay reclamos activos 
        // que dependan de esta política antes de la eliminación física. 
        // Por ahora, se mantiene la eliminación directa, pero es un riesgo de integridad.
        // ***********************************************************************************
        
        $sla->delete();

        return redirect()->route('gerente.sla-politicas')->with('success', 'Política SLA eliminada correctamente');
    }
}