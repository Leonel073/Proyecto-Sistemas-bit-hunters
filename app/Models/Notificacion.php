<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    protected $primaryKey = 'idNotificacion';

    public $timestamps = false;

    protected $fillable = [
        'idReclamo',
        'idUsuario',
        'canalEnvio',
        'mensaje',
        'fechaEnvio',
        'estadoEnvio',
    ];

    protected $casts = [
        'fechaEnvio' => 'datetime',
    ];
}
