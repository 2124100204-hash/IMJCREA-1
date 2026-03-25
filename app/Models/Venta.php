<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
protected $fillable = [
    'user_id',
    'libro_id',
    'libro_titulo',
    'libro_autor',
    'formato',
    'precio',
    'direccion_entrega'
];

// Relación con el usuario
public function user() {
    return $this->belongsTo(User::class);
}
}
