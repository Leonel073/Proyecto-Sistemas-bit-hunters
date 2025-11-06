<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatTipoIncidente extends Model
{
    use HasFactory;

    protected $table = 'cat_tipo_incidente';
    protected $primaryKey = 'idTipoIncidente';
    public $timestamps = false;

    protected $fillable = [
        'nombreIncidente',
        'descripcion'
    ];
}
