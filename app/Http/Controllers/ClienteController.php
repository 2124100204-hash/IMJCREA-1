<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Pedido;
use App\Models\Usuario;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function dashboard()
    {
        /** @var Usuario $usuario */
        $usuario = Auth::user();
        $cliente = $usuario->cliente;

        $missingFields = [];
        if (!$usuario->nombre) {
            $missingFields[] = 'Nombre';
        }
        if (!$usuario->email) {
            $missingFields[] = 'Correo';
        }
        if (!$cliente) {
            $missingFields[] = 'Perfil de cliente';
        } else {
            if (!$cliente->telefono) {
                $missingFields[] = 'Teléfono';
            }
            if (!$cliente->correo) {
                $missingFields[] = 'Correo en perfil';
            }
        }

        // Libros del usuario: detalles de pedidos entregados
        $detallesEntregados = $usuario->pedidos()
            ->where('estado', 'entregado')
            ->with('detalles.libro')
            ->get()
            ->pluck('detalles')
            ->flatten()
            ->where('estado', '!=', 'devuelto');
        
        $libros = $detallesEntregados->map->libro->unique('id');

        $favoritosIds = $usuario->favoritos ?? [];
        $favoritos = Libro::whereIn('id', $favoritosIds)->get();

        // Pedidos del usuario con sus detalles y libros
        $pedidos = Pedido::where('usuario_id', $usuario->id)
            ->with(['detalles.libro', 'detalles.devoluciones'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cliente.dashboard', compact('detallesEntregados', 'favoritos', 'usuario', 'pedidos', 'cliente', 'missingFields'));
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