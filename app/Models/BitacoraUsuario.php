<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BitacoraUsuario extends Model
{
    protected $table = 'bitacora_usuarios';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'accion',
        'usuario_modificador',
        'fecha',
    ];
}
