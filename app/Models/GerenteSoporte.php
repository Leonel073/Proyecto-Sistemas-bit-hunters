<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GerenteSoporte extends Model
{
    use HasFactory;

    protected $table = 'gerentes_soporte';

    protected $primaryKey = 'idEmpleado';

    public $timestamps = false;

    protected $fillable = [
        'idEmpleado',
        'nivelAutoridad',
    ];
}
