<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Pedido;
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

        $favoritosIds = $usuario->favoritos ?? [];
        $favoritos = Libro::whereIn('id', $favoritosIds)->get();

        // Pedidos del usuario con sus detalles y libros
        $pedidos = Pedido::where('usuario_id', $usuario->id)
            ->with('detalles.libro')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cliente.dashboard', compact('libros', 'favoritos', 'usuario', 'pedidos'));
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