<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetallePedido extends Model
{
    protected $table = 'detalle_pedidos';

    protected $fillable = ['pedido_id', 'libro_id'];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function libro(): BelongsTo
    {
        return $this->belongsTo(Libro::class, 'libro_id');
    }
}