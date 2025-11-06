<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'ipOrigen'
    ];
}
