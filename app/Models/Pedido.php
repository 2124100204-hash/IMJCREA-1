<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = ['usuario_id', 'estado', 'total'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function detalles()
    {
        return $this->hasMany(PedidoDetalle::class, 'pedido_id');
    }

    public function devoluciones()
    {
        return $this->hasManyThrough(Devolucion::class, PedidoDetalle::class, 'pedido_id', 'pedido_detalle_id');
    }
}
