<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmpleadoController extends Controller
{
    /**
     * Crear empleado + usuario vinculado
     */
    public function crear(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|max:255',
            'email'        => 'required|email|unique:usuarios,email',
            'password'     => 'required|string|min:6',
            'puesto'       => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
            'salario'      => 'nullable|numeric|min:0',
        ]);

        // 1. Crear usuario
        $usuario = Usuario::create([
            'nombre'   => $request->nombre,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'rol'      => 'empleado',
            'activo'   => true,
        ]);

        // 2. Crear empleado vinculado
        Empleado::create([
            'usuario_id'   => $usuario->id,
            'nombre'       => $request->nombre,
            'puesto'       => $request->puesto,
            'departamento' => $request->departamento,
            'salario'      => $request->salario ?? 0,
        ]);

        return back()->with('success', 'Empleado registrado correctamente.');
    }

    /**
     * Actualizar empleado + usuario
     */
    public function actualizar(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);
        $usuario  = $empleado->usuario;

        $request->validate([
            'nombre'       => 'required|string|max:255',
            'email'        => 'required|email|unique:usuarios,email,' . $usuario->id,
            'puesto'       => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
            'salario'      => 'nullable|numeric|min:0',
        ]);

       
        $userData = [
            'nombre' => $request->nombre,
            'email'  => $request->email,
        ];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $usuario->update($userData);

        $empleado->update([
            'nombre'       => $request->nombre,
            'puesto'       => $request->puesto,
            'departamento' => $request->departamento,
            'salario'      => $request->salario ?? $empleado->salario,
        ]);

        return back()->with('success', 'Empleado actualizado correctamente.');
    }

  
    public function eliminar($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->usuario()->delete();

        return back()->with('success', 'Empleado eliminado correctamente.');
    }
}