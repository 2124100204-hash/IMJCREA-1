<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibroFormato extends Model
{
    // FUERZA el nombre de la tabla si es diferente en tu DB
    protected $table = 'libro_formatos'; 

    protected $fillable = [
        'libro_id',
        'formato',
        'stock',
        'precio'
    ];

    public function libro()
    {
        return $this->belongsTo(Libro::class);
    }
}