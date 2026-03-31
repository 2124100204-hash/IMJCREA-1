<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoDetalle extends Model
{
    protected $fillable = ['pedido_id', 'libro_id', 'cantidad', 'precio_unitario', 'estado', 'formato'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function libro()
    {
        return $this->belongsTo(Libro::class, 'libro_id');
    }

    public function devoluciones()
    {
        return $this->hasMany(Devolucion::class, 'pedido_detalle_id');
    }
}
