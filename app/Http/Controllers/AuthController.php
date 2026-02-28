<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function mostrarLogin()
    {
        return view('auth.login');
    }

   public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required'
        ]);

        $usuario = Usuario::where('username', $request->username)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()->withErrors(['mensaje' => 'Usuario o contraseña incorrectos']);
        }

        // 1. Primero Auth::login (puede regenerar sesión internamente)
        Auth::login($usuario, $request->filled('remember'));

        // 2. DESPUÉS guardar en sesión, para que no se pierda
        session([
            'usuario_id'     => $usuario->id,
            'usuario_rol'    => $usuario->rol,
            'usuario_nombre' => $usuario->nombre,
            'usuario_email'  => $usuario->email,
        ]);

        // 3. Redirección por rol
        return match($usuario->rol) {
            'admin'    => redirect()->route('admin.dashboard'),
            'empleado' => redirect()->route('empleado.dashboard'),
            'cliente'  => redirect()->route('cliente.dashboard'),
            default    => redirect()->route('welcome'),
        };
    }
    public function mostrarRegistro()
    {
        return view('auth.register');
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:usuarios,email',
            'user_code' => 'required|string|max:255|unique:usuarios,codigo',
            'password'  => 'required|string|min:8|confirmed',
        ]);

        Usuario::create([
            'name'      => $request->name,
            'username'  => $request->user_code,
            'email'     => $request->email,
            'codigo'    => $request->user_code,
            'password'  => Hash::make($request->password),
            'rol'       => 'cliente',
            'nombre'    => $request->name,
            'activo'    => true,
        ]);

        return redirect()->route('mostrarLogin')->with('success', 'Registro exitoso. Ahora puedes iniciar sesión.');
    }

    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('mostrarLogin');
    }
}