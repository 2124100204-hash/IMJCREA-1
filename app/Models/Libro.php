<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $fillable = [
        'titulo',
        'descripcion',
        'autor_id',
        'nivel_edad',
        'duracion',
        'categoria',
    ];

    public function autor()
    {
        return $this->belongsTo(Autor::class);
    }

    public function formatos()
    {
        return $this->hasMany(LibroFormato::class);
    }
}