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
        'areaEnfoque'
    ];
}
