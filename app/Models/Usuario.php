<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $fillable = [
        'username',
        'email',
        'password',
        'nombre',
        'rol',
        'activo'
    ];

    protected $hidden = [
        'password',
    ];

    public function esAdmin()
    {
        return $this->rol === 'admin';
    }

    public function esEmpleado()
    {
        return $this->rol === 'empleado';
    }
}
