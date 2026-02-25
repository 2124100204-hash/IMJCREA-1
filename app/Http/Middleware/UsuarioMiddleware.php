<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UsuarioMiddleware
{
    public function handle(Request $request, Closure $next, $tipo = null)
    {
        $usuario = Session::get('usuario');

        // Si no hay sesión
        if (!$usuario) {
            return redirect()->route('login');
        }

        // Si se especifica tipo y no coincide
        if ($tipo && $usuario->tipo_usuario !== $tipo) {
            abort(403, 'No tienes permiso para acceder aquí');
        }

        return $next($request);
    }
}