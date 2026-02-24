<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Cambiamos a Authenticatable para que funcione con el Login
class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    // 🔥 ESTO ES LO QUE ARREGLA LOS NULLS 🔥
    protected $fillable = [
        'name',          // Agregado
        'email',         // Agregado
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
