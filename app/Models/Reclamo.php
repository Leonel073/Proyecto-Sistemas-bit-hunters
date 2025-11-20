<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamo extends Model
{
    use HasFactory;

    protected $table = 'reclamos';
    protected $primaryKey = 'idReclamo';
    public $timestamps = false;

    protected $fillable = [
        'idUsuario',
        'idOperador',
        'idTecnicoAsignado',
        'idPoliticaSLA',
        'idTipoIncidente',
        'idCausaRaiz',
        'titulo',
        'descripcionDetallada',
        'solucionTecnica',
        'estado',
        'prioridad',
        'latitudIncidente',
        'longitudIncidente',
        'fechaCreacion',
        'fechaResolucion',
        'fechaCierre',
        'fechaActualizacion',
        'fechaEliminacion'
    ];

    // Relaciones opcionales (ejemplo)
   public function usuario()
    {
        // Un Reclamo pertenece a un Usuario (Cliente)
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    /**
     * Obtiene el Operador (Empleado) que tomó el reclamo.
     */
    public function operador()
    {
        // Un Reclamo pertenece a un Empleado (Operador)
        return $this->belongsTo(Empleado::class, 'idOperador', 'idEmpleado');
    }

    /**
     * Obtiene el Técnico (Empleado) al que se le asignó el reclamo.
     */
    public function tecnico()
    {
        // Un Reclamo pertenece a un Empleado (Técnico)
        return $this->belongsTo(Empleado::class, 'idTecnicoAsignado', 'idEmpleado');
    }
}
