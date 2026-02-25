<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthCustom
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('usuario_id')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}