<?php

namespace App\Http\Controllers;

use App\Models\Libro;

class LibroController extends Controller
{
    public function porTipo($tipo)
    {
        $libros = Libro::where('tipo', $tipo)->get();

        return view('storebook', compact('libros'));
    }
}