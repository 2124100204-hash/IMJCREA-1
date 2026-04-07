<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Canje extends Model
{
    // Añade esta línea:
    protected $table = 'canjes';

    protected $fillable = ['usuario_id', 'cupon_id'];
}