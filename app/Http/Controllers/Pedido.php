<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = ['usuario_id'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetallePedido::class, 'pedido_id');
    }
}