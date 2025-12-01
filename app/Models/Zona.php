<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    use HasFactory;

    protected $table = 'zonas';
    protected $primaryKey = 'idZona';
    public $timestamps = true;

    protected $fillable = [
        'nombreZona',
        'descripcion',
        'estado',
    ];
}
