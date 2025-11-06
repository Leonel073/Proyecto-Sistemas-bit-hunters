<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Empleado extends Authenticatable
{
    protected $table = 'empleados';
    protected $primaryKey = 'idEmpleado';

    protected $fillable = [
        'primerNombre',
        'segundoNombre',
        'apellidoPaterno',
        'apellidoMaterno',
        'ci',
        'numeroCelular',
        'emailCorporativo',
        'passwordHash',
        'rol',
        'estado',
        'fechaIngreso'
    ];

    // ⚠️ Esto es lo importante:
    public function getAuthPassword()
    {
        return $this->passwordHash;
    }
}
