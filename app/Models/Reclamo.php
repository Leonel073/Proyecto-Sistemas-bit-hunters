<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamo extends Model
{
    use HasFactory;

    protected $table = 'reclamos';
    protected $primaryKey = 'idReclamo';
    
    // Mapeo de timestamps personalizados según las migraciones
    public const CREATED_AT = 'fechaCreacion';
    public const UPDATED_AT = 'fechaActualizacion';
    public $timestamps = true;

    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaActualizacion';

    // Los campos que se pueden llenar masivamente
    protected $fillable = [
        'idUsuario',
        'idOperador',
        'idTecnicoAsignado',
        'idPoliticaSLA',
        'idTipoIncidente', // O 'tipoIncidente' string, según tu última migración
        'titulo',
        'descripcionDetallada',
        'solucionTecnica',
        'estado',
        'prioridad',
        'latitudIncidente',
        'longitudIncidente',
        'comentarios', // <--- Agregamos esto para que funcione lo del chat
        'fechaResolucion',
        'fechaCierre',
        'fechaEliminacion'
    ];

    // Casting para que 'comentarios' se maneje como array automáticamente
    protected $casts = [
        'comentarios' => 'array',
        'fechaCreacion' => 'datetime',
        'fechaResolucion' => 'datetime',
    ];

    // ==========================================
    // RELACIONES QUE FALTABAN (AQUÍ ESTÁ LA SOLUCIÓN)
    // ==========================================

    /**
     * Relación: Un Reclamo pertenece a un Operador (que es un Empleado)
     */
    public function operador()
    {
        // 'idOperador' es la FK en reclamos, 'idEmpleado' es la PK en empleados
        return $this->belongsTo(Empleado::class, 'idOperador', 'idEmpleado');
    }

    /**
     * Relación: Un Reclamo pertenece a un Técnico Asignado (que es un Empleado)
     */
    public function tecnico()
    {
        return $this->belongsTo(Empleado::class, 'idTecnicoAsignado', 'idEmpleado');
    }

    /**
     * Relación: Un Reclamo pertenece a un Usuario (Cliente)
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
    
    /**
     * Relación: Un Reclamo pertenece a una Política SLA
     */
    public function politicaSLA()
    {
        return $this->belongsTo(SlaPolitica::class, 'idPoliticaSLA', 'idPoliticaSLA');
    }
}