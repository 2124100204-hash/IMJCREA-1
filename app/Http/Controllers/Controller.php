<?php

namespace App\Http\Controllers;

abstract class Controller
{
 public function realidadAumentada()
{
    $libros = Libro::where('tipo', 'ar')->get();
    return view('store.augmented-reality', compact('libros'));
}
}
