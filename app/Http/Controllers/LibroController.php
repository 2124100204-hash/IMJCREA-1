<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    public function porTipo($tipo)
    {
        $libros = Libro::where('tipo', $tipo)->get();

        return view('storebook', compact('libros'));
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