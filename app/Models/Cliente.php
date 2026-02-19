<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'usuario_id',
        'nombre',
        'telefono',
        'direccion'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
