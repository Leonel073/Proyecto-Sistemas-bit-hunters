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
        return $this->belongsTo(Usuario::class, 'idUsuario');
    }

    public function tecnico()
    {
        return $this->belongsTo(Empleado::class, 'idTecnicoAsignado', 'idEmpleado');
    }
}
