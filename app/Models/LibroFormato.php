<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibroFormato extends Model
{
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