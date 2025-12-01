<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operador extends Model
{
    use HasFactory;

    protected $table = 'operadores';

    protected $primaryKey = 'idEmpleado';

    public $timestamps = false;

    protected $fillable = [
        'idEmpleado',
        'turno',
    ];

    public function empleado()
    {
        // Un Operador (o Tecnico) pertenece a un Empleado
        return $this->belongsTo(Empleado::class, 'idEmpleado', 'idEmpleado');
    }
}
