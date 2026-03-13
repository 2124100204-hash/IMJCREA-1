<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Autor;
use App\Models\LibroFormato;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    public function index()
    {
        $libros = Libro::with(['autor', 'formatos'])->get();

        return view('storebook', [
            'libros' => $libros,
            'filterTipo' => null,
        ]);
    }

    public function show($id)
    {
        $libro = Libro::with(['autor', 'formatos'])->findOrFail($id);

        return view('details', [
            'libro' => $libro,
        ]);
    }

    public function tipo($tipo)
    {
        $libros = Libro::with(['autor', 'formatos'])
            ->whereHas('formatos', function ($query) use ($tipo) {
                $query->where('formato', $tipo);
            })
            ->get();

        return view('storebook', [
            'libros' => $libros,
            'filterTipo' => $tipo,
        ]);
    }

    // Crear libro correctamente
    public function crear(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'formato' => 'required|string|max:50',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        // Crear o buscar autor
        $autor = Autor::firstOrCreate([
            'nombre' => $request->autor
        ]);

        // Crear libro
        $libro = Libro::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'autor_id' => $autor->id,
            'nivel_edad' => 'general',
            'duracion' => 0,
            'categoria' => 'General',
        ]);

        // Crear formato
        LibroFormato::create([
            'libro_id' => $libro->id,
            'formato' => $request->formato,
            'precio' => $request->precio,
            'stock' => $request->stock,
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
    $libro = Libro::findOrFail($id);

    // 1. Actualizar datos del libro
    $libro->update([
        'titulo' => $request->titulo,
        'descripcion' => $request->descripcion,
        'nivel_edad' => $request->nivel_edad,
        'duracion' => $request->duracion,
        'autor_id' => $request->autor_id,
        'categoria_id' => $request->categoria_id,
    ]);

    // 2. Gestionar Formatos (Físico, VR, AR)
    // Borramos los formatos actuales para insertar los nuevos seleccionados
    \App\Models\LibroFormato::where('libro_id', $id)->delete();

    if ($request->has('formatos')) {
        foreach ($request->formatos as $formato) {
            \App\Models\LibroFormato::create([
                'libro_id' => $id,
                'formato' => $formato,
                'stock' => 999, // Puedes cambiar esto por un input del request si lo deseas
                'precio' => 0   // Igual aquí
            ]);
        }
    }

    return back()->with('success', '¡Libro actualizado con éxito!');
}
}