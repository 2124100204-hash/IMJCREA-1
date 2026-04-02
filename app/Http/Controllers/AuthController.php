<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
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
public function handleGoogleCallback() {
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // 1. Verificar si existe en tabla usuarios
        $usuario = Usuario::where('email', $googleUser->email)->first();

        if (!$usuario) {
            // Crear en tabla usuarios
            $usuario = Usuario::create([
                'nombre'   => $googleUser->name,
                'email'    => $googleUser->email,
                'username' => explode('@', $googleUser->email)[0], 
                'password' => Hash::make(Str::random(16)),
                'rol'      => 'cliente',
                'activo'   => true,
            ]);
        }

        // 2. Verificar si existe en tabla clientes
        $cliente = Cliente::where('usuario_id', $usuario->id)->first();

        if (!$cliente) {
            // Crear en tabla clientes
            Cliente::create([
                'usuario_id' => $usuario->id,
                'nombre'     => $googleUser->name,
                'correo'     => $googleUser->email,
                'telefono'   => null,
            ]);
        }

        // 3. Login y sesión
        Auth::login($usuario);
        session([
            'usuario_id'     => $usuario->id,
            'usuario_rol'    => $usuario->rol,
            'usuario_nombre' => $usuario->nombre,
            'usuario_email'  => $usuario->email,
        ]);

        return redirect()->route('cliente.dashboard');

    } catch (\Exception $e) {
        return redirect()->route('mostrarLogin')->withErrors(['mensaje' => 'Error con Google: ' . $e->getMessage()]);
    }
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