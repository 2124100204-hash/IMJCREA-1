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
}