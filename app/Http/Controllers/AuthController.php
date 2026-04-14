<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
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

            $usuario = DB::transaction(function () use ($googleUser) {
                $usuario = Usuario::firstOrCreate(
                    ['email' => $googleUser->email],
                    [
                        'nombre'   => $googleUser->name,
                        'username' => explode('@', $googleUser->email)[0],
                        'password' => Hash::make(Str::random(16)),
                        'rol'      => 'cliente',
                        'activo'   => true,
                    ]
                );

                if ($usuario->rol !== 'cliente') {
                    $usuario->rol = 'cliente';
                    $usuario->save();
                }

                $this->crearPerfilCliente($usuario, $googleUser->email, $googleUser->name);

                return $usuario;
            });

            Auth::login($usuario);
            session([
                'usuario_id'     => $usuario->id,
                'usuario_rol'    => $usuario->rol,
                'usuario_nombre' => $usuario->nombre,
                'usuario_email'  => $usuario->email,
            ]);

            return redirect()->route('cliente.dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['mensaje' => 'Error con Google: ' . $e->getMessage()]);
        }
    }
 public function registrar(Request $request)
{
  $request->validate([
    'name'     => 'required|string|max:255',
    'email'    => 'required|string|email|max:255|unique:usuarios,email',
    'password' => 'required|string|min:8|confirmed',
], [
    'name.required'             => 'El nombre es obligatorio.',
    'name.max'                  => 'El nombre no puede superar 255 caracteres.',
    'email.required'            => 'El correo electrónico es obligatorio.',
    'email.email'               => 'El correo electrónico no tiene un formato válido.',
    'email.unique'              => 'Este correo ya está registrado.',
    'password.required'         => 'La contraseña es obligatoria.',
    'password.min'              => 'La contraseña debe tener al menos 8 caracteres.',
    'password.confirmed'        => 'Las contraseñas no coinciden.',
]);
    $usuario = DB::transaction(function () use ($request) {
        // Generar username único a partir del email
        $username = Str::slug(explode('@', $request->email)[0]);

        $baseUsername = $username;
        $count = 1;
        while (Usuario::where('username', $username)->exists()) {
            $username = $baseUsername . $count++;
        }

        $usuario = Usuario::create([
            'nombre'   => $request->name,
            'username' => $username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'rol'      => 'cliente',
            'activo'   => true,
        ]);

        $this->crearPerfilCliente($usuario, $request->email, $request->name);

        return $usuario;
    });

    return redirect()->route('login')->with('success', 'Registro exitoso. Ahora puedes iniciar sesión.');
}
    private function crearPerfilCliente(Usuario $usuario, string $correo, string $nombre)
    {
        $cliente = Cliente::where('usuario_id', $usuario->id)->first();

        if (!$cliente) {
            $cliente = Cliente::where('correo', $correo)->first();

            if ($cliente) {
                $cliente->update([
                    'usuario_id' => $usuario->id,
                    'nombre'     => $nombre,
                    'telefono'   => $cliente->telefono,
                ]);
                return;
            }

            Cliente::create([
                'usuario_id' => $usuario->id,
                'nombre'     => $nombre,
                'correo'     => $correo,
                'telefono'   => null,
            ]);
        }
    }

    public function actualizarPerfil(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:usuarios,email,' . $usuario->id,
            'telefono' => 'nullable|string|max:30',
        ]);

        // Sanitizar teléfono: solo números, espacios, guiones y paréntesis
        $telefono = $request->telefono;
        if ($telefono) {
            $telefono = preg_replace('/[^\d\s\-\(\)\+]/', '', $telefono);
            $telefono = trim($telefono);
        }

        $usuario->nombre = $request->nombre;
        $usuario->email = $request->email;
        $usuario->save();

        $cliente = Cliente::firstOrNew(['usuario_id' => $usuario->id]);
        $cliente->nombre = $request->nombre;
        $cliente->correo = $request->email;
        $cliente->telefono = $telefono;
        $cliente->save();

        session([
            'usuario_nombre' => $usuario->nombre,
            'usuario_email'  => $usuario->email,
        ]);

        return redirect()->back()->with('success', 'Tus datos se actualizaron correctamente.');
    }

    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('login');
    }
}