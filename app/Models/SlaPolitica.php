<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaPolitica extends Model
{
    use HasFactory;

    protected $table = 'sla_politicas';

    protected $primaryKey = 'idPoliticaSLA';

    public $timestamps = false;

    protected $fillable = [
        'nombrePolitica',
        'prioridad',
        'tiempoMaxSolucionHoras',
        'estaActiva',
    ];
}
