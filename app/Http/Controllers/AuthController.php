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
            'username' => 'required',
            'password' => 'required'
        ]);

        $usuario = Usuario::where('username', $request->username)
                          ->where('activo', true)
                          ->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()->withErrors(['mensaje' => 'Usuario o contraseña incorrectos']);
        }

        // Usar el sistema de autenticación nativo de Laravel
        Auth::login($usuario, $request->filled('remember'));
        
        // También guardar en sesión para compatibilidad
        session([
            'usuario_id' => $usuario->id,
            'usuario_rol' => $usuario->rol,
            'usuario_nombre' => $usuario->nombre,
            'usuario_username' => $usuario->username,
            'usuario' => $usuario
        ]);

        if ($usuario->rol === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($usuario->rol === 'empleado') {
            return redirect()->route('empleado.dashboard');
        } elseif ($usuario->rol === 'cliente') {
            return redirect()->route('cliente.dashboard');
        } else {
            return redirect()->route('welcome');
        }
    }

    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('login');
    }
}