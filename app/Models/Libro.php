<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $table = 'libros';
    protected $fillable = ['titulo', 'autor', 'descripcion', 'formato', 'usuario_id'];
    public $timestamps = true;
}