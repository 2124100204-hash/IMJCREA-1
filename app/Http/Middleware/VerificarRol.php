<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarRol
{
    public function handle(Request $request, Closure $next, $rol)
    {
        if (session('usuario_rol') !== $rol) {
            return redirect('/login')->withErrors(['mensaje' => 'No tienes permiso']);
        }

        return $next($request);
    }
}

