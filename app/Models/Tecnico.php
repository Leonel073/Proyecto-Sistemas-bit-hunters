<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tecnico extends Model
{
    use HasFactory;

    protected $table = 'tecnicos';

    protected $primaryKey = 'idEmpleado';

    public $timestamps = false;

    protected $fillable = [
        'idEmpleado',
        'especialidad',
        'estadoDisponibilidad',
        'latitud',
        'longitud',
    ];

    public function empleado()
    {
        // Un Operador (o Tecnico) pertenece a un Empleado
        return $this->belongsTo(Empleado::class, 'idEmpleado', 'idEmpleado');
    }
}
