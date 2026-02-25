<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UsuarioAuth
{
   public function handle(Request $request, Closure $next, $tipo = null)
{
    if (!Session::has('usuario')) {
        return redirect('/login');
    }

    $usuario = Session::get('usuario');

    if ($tipo && $usuario->tipo_usuario !== $tipo) {
        return redirect('/login');
    }

    return $next($request);
}
}