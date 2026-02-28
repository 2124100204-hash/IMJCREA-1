<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Muestra la vista de inicio de sesión.
     */
    public function mostrarLogin()
    {
        return view('auth.login');
    }

    /**
     * Procesa el inicio de sesión.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Buscamos al usuario por email (columna real en tu DB)
        $usuario = Usuario::where('email', $request->email)->first();

        // Verificamos credenciales
        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()->withErrors(['mensaje' => 'Usuario o contraseña incorrectos']);
        }


        // Usar el sistema de autenticación nativo de Laravel
        Auth::login($usuario, $request->filled('remember'));
        
        // También guardar en sesión para compatibilidad

        // Manejo manual de sesión con los nombres de tu tabla
        session([
            'usuario_id' => $usuario->id,
            'usuario_rol' => $usuario->tipo_usuario, 
            'usuario_nombre' => $usuario->name,       
            'usuario_email' => $usuario->email,
            'usuario' => $usuario
        ]);

        // Redirección por rol
        if ($usuario->tipo_usuario === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($usuario->rol === 'empleado') {
            return redirect()->route('empleado.dashboard');
        } elseif ($usuario->rol === 'cliente') {
            return redirect()->route('cliente.dashboard');
        } else {
            return redirect()->route('welcome');
        }
    }

    // --- FUNCIONES DE REGISTRO ---

    /**
     * Muestra la vista de registro.
     */
    public function mostrarRegistro()
    {
        return view('auth.register');
    }

    /**
     * Procesa la creación de un nuevo usuario.
     */
    public function registrar(Request $request)
    {
        // Validación mapeada a tu HTML y Base de Datos
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:usuarios,email',
            'user_code' => 'required|string|max:255|unique:usuarios,codigo', 
            'password'  => 'required|string|min:8|confirmed',
        ]);

        // Creación usando el Modelo Usuario
        Usuario::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'codigo'       => $request->user_code, 
            'password'     => Hash::make($request->password),
            'tipo_usuario' => 'cliente', 
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        /** * SOLUCIÓN AL ERROR: 
         * Cambiamos 'login' por 'mostrarLogin' para que coincida con ->name('mostrarLogin') 
         * de tu archivo web.php.
         */
        return redirect()->route('mostrarLogin')->with('success', 'Registro exitoso. Ahora puedes iniciar sesión.');
    }

    // --- FIN FUNCIONES DE REGISTRO ---

    /**
     * Cierra la sesión manualmente.
     */
    public function logout()
    {
        Auth::logout();
        session()->flush();
        // Cambiamos 'login' por 'mostrarLogin' para consistencia con web.php
        return redirect()->route('mostrarLogin');
    }
}