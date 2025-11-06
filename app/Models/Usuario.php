<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'USUARIOS';
    protected $primaryKey = 'idUsuario';
    public $timestamps = false;

    protected $fillable = [
        'primerNombre',
        'segundoNombre',
        'apellidoPaterno',
        'apellidoMaterno',
        'ci',
        'numeroCelular',
        'email',
        'passwordHash',
        'direccionTexto',
        'estado'
    ];

    protected $hidden = [
        'passwordHash',
    ];

    // IMPORTANTE para Auth::attempt
    public function getAuthPassword()
    {
        return $this->passwordHash;
    }
}
