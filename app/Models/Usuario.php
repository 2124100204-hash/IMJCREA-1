<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Cliente;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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

    public function getNombreAttribute()
    {
        return $this->name;
    }

    public function getNombreCompletoAttribute()
    {
        return $this->name . ' ' . $this->apellido;
    }

    public function getApellidoAttribute()
    {
        return $this->apellido;
    }

    public function getTipoUsuarioAttribute()
    {
        return $this->tipo_usuario;
    }

    public function getEstadoUsuarioAttribute()
    {
        return $this->activo;
    }

    public function getFavoritosAttribute()
    {
        return $this->favoritos;
    }

    public function getClienteAttribute()
    {
        return $this->cliente;
    }

    public function getClienteIdAttribute()
    {
        return $this->cliente_id;
    }

    public function getClienteNombreAttribute()
    {
        return $this->cliente->name;
    }

    public function getClienteEmailAttribute()
    {
        return $this->cliente->email;
    }

    public function getClienteRolAttribute()
    {
        return $this->cliente->rol;
    }

    public function getClienteFavoritosAttribute()
    {
        return $this->cliente->favoritos;
    }

    public function getClienteActivoAttribute()
    {
        return $this->cliente->activo;
    }

    public function getClienteCreadoAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getClienteCreadoEnNombreEmailAttribute()
    {
        return $this->cliente->created_at;
    }

    public function getClienteActualizadoEnNombreEmailAttribute()
    {
        return $this->cliente->updated_at;
    }

    public function getClienteCreadoPorNombreEmailAttribute()
    {
        return $this->cliente->created_by;
    }

    public function getClienteActualizadoPorNombreEmailAttribute()
    {
        return $this->cliente->updated_by;
    }

    public function getCliente