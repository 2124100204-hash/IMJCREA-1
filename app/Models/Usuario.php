<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Cliente;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    // Ajustado a las columnas reales de tu tabla: name, email, codigo, password, tipo_usuario
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'nombre',
        'rol',
        'activo',
        'favoritos',
        'codigo',
        'tipo_usuario',

    ];

    protected $hidden = [
        'password',
    ];


    protected $casts = [
        'favoritos' => 'json',
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'usuario_id');
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'usuario_id');
    }

    // Ajustado a la columna 'tipo_usuario' de tu base de datos

    public function esAdmin()
    {
        return $this->tipo_usuario === 'admin';
    }

    // Ajustado para verificar si es cliente o empleado según tu lógica
    public function esEmpleado()
    {
        return $this->tipo_usuario === 'cliente'; 
    }
}
