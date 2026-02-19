<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $fillable = [
        'usuario_id',
        'nombre',
        'puesto',
        'salario'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
