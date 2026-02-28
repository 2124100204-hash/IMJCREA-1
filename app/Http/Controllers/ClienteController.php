<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function dashboard()
    {
        /** @var Usuario $usuario */
        $usuario = Auth::user();
        $libros = Libro::all();
        
        // Obtener IDs de favoritos del usuario
        $favoritosIds = $usuario->favoritos ?? [];
        
        // Obtener los libros favoritos
        $favoritos = Libro::whereIn('id', $favoritosIds)->get();
        
        return view('cliente.dashboard', compact('libros', 'favoritos', 'usuario'));
    }

    public function tienda()
    {
        $libros = Libro::all();
        return view('storebook', [
            'libros' => $libros,
            'filterTipo' => null,
        ]);
    }
}
