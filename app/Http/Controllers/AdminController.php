<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('employer.dashboard-admin');
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
public function actualizarLibro(Request $request, $id)
{
    $libro = Libro::findOrFail($id);
    
    // Actualizar datos básicos
    $libro->update($request->only(['titulo', 'descripcion', 'nivel_edad', 'duracion', 'autor_id', 'categoria_id']));

    // Actualizar Formatos (Sincronización)
    // Asumiendo que tienes una relación Muchos a Muchos con una tabla de formatos
    if ($request->has('formatos')) {
        // Aquí deberías guardar en tu tabla 'libro_formatos' 
        // según la lógica de tu base de datos
    }

    return back()->with('success', 'Libro actualizado correctamente');
}

public function crearAutor(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255'
    ]);

    $nombreNormalizado = trim($request->nombre);

    // Comprobación de duplicados (Ignora mayúsculas/minúsculas)
    $existe = \App\Models\Autor::where('nombre', 'LIKE', $nombreNormalizado)->first();

    if ($existe) {
        return back()->with('error', 'El autor "' . $nombreNormalizado . '" ya existe.');
    }

    \App\Models\Autor::create(['nombre' => $nombreNormalizado]);

    return back()->with('success', 'Autor registrado correctamente.');
}
public function actualizarEmpleado(Request $request, $id)
{
    $empleado = \App\Models\Usuario::findOrFail($id);
    $request->validate([
        'nombre' => 'required|string|max:255',
        'email' => 'required|email|unique:usuarios,email,' . $id, 
    ]);

    $empleado->update([
        'nombre' => $request->nombre,
        'email' => $request->email, 
        'password' => $request->password ? bcrypt($request->password) : $empleado->password,
    ]);

    return back()->with('success', 'Empleado actualizado correctamente.');
}

    }