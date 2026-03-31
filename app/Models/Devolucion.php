<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    protected $table = 'devoluciones';

    protected $fillable = ['pedido_detalle_id', 'cantidad_devuelta', 'monto_reembolsado', 'razon', 'estado'];

    public function pedidoDetalle()
    {
        return $this->belongsTo(PedidoDetalle::class, 'pedido_detalle_id');
    }
}
