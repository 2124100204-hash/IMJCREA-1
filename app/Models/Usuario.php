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
        'activo',
        'favoritos'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'favoritos' => 'json',
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
