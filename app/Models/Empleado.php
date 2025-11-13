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

    /**
     * Obtiene el perfil específico de Supervisor de Operadores.
     */
    public function supervisorOperador()
    {
        // Un Empleado TIENE UN perfil de SupervisorOperador
        return $this->hasOne(SupervisorOperador::class, 'idEmpleado', 'idEmpleado');
    }

    /**
     * (Recomendado) Añade también las relaciones para los otros roles
     */
    public function operador()
    {
        return $this->hasOne(Operador::class, 'idEmpleado', 'idEmpleado');
    }
    
    public function tecnico()
    {
        return $this->hasOne(Tecnico::class, 'idEmpleado', 'idEmpleado');
    }
}
