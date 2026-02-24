<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nombre de la tabla en phpMyAdmin.
     * Vincula este modelo con la tabla 'usuarios'.
     */
    protected $table = 'usuarios';

    /**
     * Atributos que se pueden asignar masivamente.
     * Se incluyen 'name' y 'email' para que dejen de guardarse como NULL.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'codigo',
        'tipo_usuario', 
    ];

    /**
     * Atributos que deben ocultarse para la serialización.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Define los tipos de datos (casts) de los atributos.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}