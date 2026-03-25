<?php

namespace App\Http\Controllers;

use App\Models\LibroFormato;
use App\Models\Venta;
use App\Models\Usuario;
use App\Models\Autor;    
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;
class AdminController extends Controller
{
public function crearLibro(Request $request)
{
    try {
        // 1. Manejo del Autor (Si el usuario escribió uno nuevo en el otro modal o si viene del select)
        $autorId = $request->autor_id;

        // 2. Manejo de Categoría (Ignorando Mayúsculas/Minúsculas)
        $categoriaId = null;
        if ($request->filled('categoria_nombre')) {
            $nombreCat = trim($request->categoria_nombre);
            // Buscamos la categoría sin importar A/a
            $categoria = \App\Models\Categoria::whereRaw('LOWER(nombre) = ?', [strtolower($nombreCat)])->first();
            
            if (!$categoria) {
                $categoria = \App\Models\Categoria::create(['nombre' => $nombreCat]);
            }
            $categoriaId = $categoria->id;
        }

        // 3. Crear el Libro
        $libro = new \App\Models\Libro();
        $libro->titulo = $request->titulo;
        $libro->autor_id = $autorId;
        $libro->categoria_id = $categoriaId;
        $libro->descripcion = $request->descripcion;
        $libro->nivel_edad = $request->nivel_edad;
        $libro->duracion = $request->duracion;

        // Subida de imagen
        if ($request->hasFile('portada')) {
            $libro->portada = $request->file('portada')->store('portadas', 'public');
        }

        $libro->save(); // 🚩 CRÍTICO: Guardar aquí para generar el ID del libro

        // 4. Crear los registros en 'libro_formatos'
        if ($request->has('formatos')) {
            foreach ($request->formatos as $tipo => $datos) {
                // Solo si el checkbox 'activo' fue marcado
                if (isset($datos['activo'])) {
                    \App\Models\LibroFormato::create([
                        'libro_id' => $libro->id,
                        'formato'  => $tipo, // 'fisico', 'ar', 'vr'
                        'stock'    => $datos['stock'] ?? 0,
                        'precio'   => $datos['precio'] ?? 0,
                    ]);
                }
            }
        }

        return back()->with('success', '¡Libro y formatos registrados exitosamente!');

    } catch (\Exception $e) {
        // Si hay un error de SQL (columna faltante, etc), esto lo atrapará
        return back()->with('error', 'Error al guardar: ' . $e->getMessage());
    }
}
    public function dashboard()
    {
    $ventas = Venta::orderBy('created_at', 'desc')->get();
    return view('employer.dashboard-admin', compact('ventas'));

    }

    public function libros()
    {
        return view('employer.dashboard-admin');
    }

    public function usuarios()
    {
        return view('employer.dashboard-admin');
    }

    public function formatos()
    {
        return view('employer.dashboard-admin');
    }
public function crearAutor(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:autores,nombre'
        ]);

        Autor::create($request->all());

        return back()->with('success', 'Autor añadido correctamente.');
    }
    public function crearCategoria(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:categorias,nombre'
        ]);

        Categoria::create($request->all());

        return back()->with('success', 'Categoría añadida correctamente.');
    }
    public function crearEmpleado(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:usuarios',
            'email' => 'required|email|unique:usuarios',
            'nombre' => 'required',
            'password' => 'required|min:6'
        ]);

        Usuario::create([
            'username' => $request->username,
            'email' => $request->email,
            'nombre' => $request->nombre,
            'password' => Hash::make($request->password),
            'rol' => 'empleado',
            'activo' => true
        ]);

        return back()->with('success', 'Empleado creado correctamente');
    }

    public function eliminarEmpleado($id)
    {
        $empleado = Usuario::findOrFail($id);
        
        if ($empleado->rol === 'empleado') {
            $empleado->delete();
            return back()->with('success', 'Empleado eliminado correctamente');
        }

        return back()->with('error', 'No se puede eliminar este usuario');
    }
}