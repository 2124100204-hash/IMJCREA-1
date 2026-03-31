<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibroController extends Controller
{
    public function index()
    {
        $libros = Libro::all();
        $ownedLibroIds = [];

        if (Auth::check()) {
            $usuario = Auth::user();

            $pedidosEntregados = Pedido::where('usuario_id', $usuario->id)
                ->where('estado', 'entregado')
                ->pluck('id');

            $ownedDetalleByLibro = PedidoDetalle::whereIn('pedido_id', $pedidosEntregados)
                ->where('estado', '!=', 'devuelto')
                ->get()
                ->groupBy('libro_id')
                ->map(fn($group) => $group->first()->id)
                ->toArray();

            $ownedLibroIds = array_keys($ownedDetalleByLibro);
        }

        return view('storebook', [
            'libros' => $libros,
            'filterTipo' => null,
            'ownedLibroIds' => $ownedLibroIds,
            'ownedDetalleByLibro' => $ownedDetalleByLibro ?? [],
        ]);
    }

    public function show($id)
    {
        $libro = Libro::with('formatos')->findOrFail($id);
        $ownedDetalle = null;

        if (Auth::check()) {
            $usuario = Auth::user();

            $ownedDetalle = PedidoDetalle::whereHas('pedido', function ($query) use ($usuario) {
                $query->where('usuario_id', $usuario->id)
                      ->where('estado', 'entregado');
            })
            ->where('libro_id', $libro->id)
            ->where('estado', '!=', 'devuelto')
            ->first();
        }

        return view('details', [
            'libro' => $libro,
            'ownedDetalle' => $ownedDetalle,
        ]);
    }

    public function tipo($tipo)
    {
        $libros = Libro::whereHas('formatos', function ($query) use ($tipo) {
            $query->where('formato', $tipo);
        })->get();

        $ownedLibroIds = [];
        $ownedDetalleByLibro = [];

        if (Auth::check()) {
            $usuario = Auth::user();

            $pedidosEntregados = Pedido::where('usuario_id', $usuario->id)
                ->where('estado', 'entregado')
                ->pluck('id');

            $ownedDetalleByLibro = PedidoDetalle::whereIn('pedido_id', $pedidosEntregados)
                ->where('estado', '!=', 'devuelto')
                ->get()
                ->groupBy('libro_id')
                ->map(fn($group) => $group->first()->id)
                ->toArray();

            $ownedLibroIds = array_keys($ownedDetalleByLibro);
        }

        return view('storebook', [
            'libros' => $libros,
            'filterTipo' => $tipo,
            'ownedLibroIds' => $ownedLibroIds,
            'ownedDetalleByLibro' => $ownedDetalleByLibro,
        ]);
    }

    public function porTipo($tipo)
    {
        // kept for backwards compatibility but now unused
        $libros = Libro::whereHas('formatos', function ($query) use ($tipo) {
            $query->where('formato', $tipo);
        })->get();

        return view('storebook', [
            'libros' => $libros,
            'filterTipo' => $tipo,
        ]);
    }

    public function crear(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'formato' => 'required|string|max:50'
        ]);

        Libro::create([
            'titulo' => $request->titulo,
            'autor' => $request->autor,
            'descripcion' => $request->descripcion,
            'formato' => $request->formato,
            'usuario_id' => session('usuario_id')
        ]);

        return back()->with('success', 'Libro creado correctamente');
    }

    public function eliminar($id)
    {
        $libro = Libro::findOrFail($id);
        $libro->delete();
        return back()->with('success', 'Libro eliminado correctamente');
    }

    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'formato' => 'required|string|max:50'
        ]);

        $libro = Libro::findOrFail($id);
        $libro->update([
            'titulo' => $request->titulo,
            'autor' => $request->autor,
            'descripcion' => $request->descripcion,
            'formato' => $request->formato
        ]);

        return back()->with('success', 'Libro actualizado correctamente');
    }
}