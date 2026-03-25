<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $fillable = [
        'titulo',
        'descripcion',
        'autor_id',
        'categoria_id',
        'nivel_edad',
        'duracion',
        'categoria',
    ];
public function categoria()
{
    return $this->belongsTo(Categoria::class, 'categoria_id');
}
    // En App\Models\Libro.php
public function autor() {
    return $this->belongsTo(Autor::class, 'autor_id'); 
}

   public function formatos()
{
    return $this->hasMany(LibroFormato::class, 'libro_id');
}
}