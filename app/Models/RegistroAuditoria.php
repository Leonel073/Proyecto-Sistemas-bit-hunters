<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empleado;
use App\Models\Usuario;
use App\Models\Reclamo;

class RegistroAuditoria extends Model
{
    use HasFactory;

    protected $table = 'registros_auditoria';

    protected $primaryKey = 'idLog';

    public $timestamps = false;

    protected $fillable = [
        'idEmpleado',
        'idUsuario',
        'idReclamoAfectado',
        'accion',
        'detalleAccion',
        'fechaHora',
        'ipOrigen',
    ];

    protected $casts = [
        'fechaHora' => 'datetime',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'idEmpleado', 'idEmpleado');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    public function reclamo()
    {
        return $this->belongsTo(Reclamo::class, 'idReclamoAfectado', 'idReclamo');
    }

    
}
