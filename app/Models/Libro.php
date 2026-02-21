<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $table = 'libros';

    protected $fillable = [
        'titulo',
        'descripcion',
        'autor',
        'nivel_edad',
        'duracion'
    ];

    public function formatos()
    {
        return $this->hasMany(\App\Models\LibroFormato::class);
    }
}