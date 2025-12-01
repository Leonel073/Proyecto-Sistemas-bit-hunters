<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisorOperador extends Model
{
    use HasFactory;

    protected $table = 'supervisores_operadores';

    protected $primaryKey = 'idEmpleado';

    public $timestamps = false;

    protected $fillable = [
        'idEmpleado',
        'areaEnfoque',
    ];

    /**
     * Obtiene los datos base del empleado (nombre, CI, email, etc.)
     */
    public function empleadoBase()
    {
        // Un SupervisorOperador PERTENECE A un Empleado
        return $this->belongsTo(Empleado::class, 'idEmpleado', 'idEmpleado');
    }
}
