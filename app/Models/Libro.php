<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\LibroFormato;

class Libro extends Model
{
    protected $table = 'libros';
    protected $fillable = ['titulo', 'autor', 'descripcion', 'formato', 'usuario_id', 'nivel_edad', 'duracion', 'categoria'];
    public $timestamps = true;

    public function formatos(): HasMany
    {
        return $this->hasMany(LibroFormato::class, 'libro_id');
    }
}