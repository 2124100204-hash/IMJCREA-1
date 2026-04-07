<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Devolucion extends Model
{
    protected $table = 'devoluciones';

    protected $fillable = [
        'pedido_detalle_id', 
        'motivo', 
        'cantidad'
    ];

    // ESTO ES LO QUE FALTA:
    public function pedidoDetalle(): BelongsTo
    {
        return $this->belongsTo(PedidoDetalle::class, 'pedido_detalle_id');
    }
}