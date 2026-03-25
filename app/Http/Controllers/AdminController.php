<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Usuario;
use App\Models\Autor;      // IMPORTANTE: Añade esta línea
use App\Models\Categoria;  // IMPORTANTE: Añade esta línea
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
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