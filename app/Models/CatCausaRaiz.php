<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatCausaRaiz extends Model
{
    use HasFactory;

    protected $table = 'cat_causa_raiz';

    protected $primaryKey = 'idCausaRaiz';

    public $timestamps = false;

    protected $fillable = [
        'nombreCausa',
        'descripcion',
    ];
}
