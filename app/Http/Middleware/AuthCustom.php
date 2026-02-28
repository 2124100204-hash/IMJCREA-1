<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthCustom
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar con el sistema de Auth de Laravel O con la sesión manual
        if (!Auth::check() && !session()->has('usuario_id')) {
            return redirect()->route('login');
        }

        // Si Auth está activo pero la sesión manual no, sincronizarla
        if (Auth::check() && !session()->has('usuario_id')) {
            $usuario = Auth::user();
            session([
                'usuario_id'     => $usuario->id,
                'usuario_rol'    => $usuario->rol,
                'usuario_nombre' => $usuario->nombre,
                'usuario_email'  => $usuario->email,
            ]);
        }

        return $next($request);
    }
}