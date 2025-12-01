<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index()
    {
        return response()->json(Notificacion::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'idReclamo' => 'required|integer',
            'idUsuario' => 'required|integer',
            'canalEnvio' => 'required|string',
            'mensaje' => 'required|string',
        ]);

        $notificacion = Notificacion::create($request->all());

        return response()->json(['message' => 'Notificación enviada', 'data' => $notificacion]);
    }

    public function show($id)
    {
        return response()->json(Notificacion::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $notificacion = Notificacion::findOrFail($id);
        $notificacion->update($request->all());

        return response()->json(['message' => 'Notificación actualizada', 'data' => $notificacion]);
    }

    /**
     * Muestra la vista de notificaciones para el cliente.
     */
    public function indexView()
    {
        $usuario = \Illuminate\Support\Facades\Auth::user();
        
        // Obtener notificaciones del usuario, ordenadas por fecha
        $notificaciones = Notificacion::where('idUsuario', $usuario->idUsuario)
            ->orderBy('fechaEnvio', 'desc')
            ->paginate(10);

        return view('cliente.notificaciones', compact('notificaciones'));
    }

    /**
     * Marca todas las notificaciones del usuario como leídas.
     */
    public function marcarTodas()
    {
        $usuario = \Illuminate\Support\Facades\Auth::user();
        
        Notificacion::where('idUsuario', $usuario->idUsuario)
            ->where('estadoEnvio', '!=', 'Leído')
            ->update(['estadoEnvio' => 'Leído']);

        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }

    public function destroy($id)
    {
        Notificacion::destroy($id);

        return response()->json(['message' => 'Notificación eliminada']);
    }
}
