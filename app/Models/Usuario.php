<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';

    protected $fillable = [
        'codigo',
        'password',
        'tipo_usuario'
    ];

    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }

    public function empleado()
    {
        return $this->hasOne(Empleado::class);
    }
}
