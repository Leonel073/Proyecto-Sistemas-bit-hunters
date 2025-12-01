<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisorTecnico extends Model
{
    use HasFactory;

    protected $table = 'supervisores_tecnicos';

    protected $primaryKey = 'idEmpleado';

    public $timestamps = false;

    protected $fillable = [
        'idEmpleado',
        'zonaGeograficaAsignada',
    ];
}
