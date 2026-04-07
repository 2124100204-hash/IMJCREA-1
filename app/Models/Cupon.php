<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupon extends Model
{
    use HasFactory;

    // ESTA LÍNEA ES LA MÁS IMPORTANTE:
    protected $table = 'cupones'; 

    protected $fillable = ['codigo', 'premio'];
}