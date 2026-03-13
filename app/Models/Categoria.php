<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoria extends Model
{
    use HasFactory;

    // 🔹 Esto permite que el comando pueda insertar el nombre
    protected $fillable = ['nombre'];

    // Relación inversa: Una categoría tiene muchos libros
    public function libros()
    {
        return $this->hasMany(Libro::class);
    }
}