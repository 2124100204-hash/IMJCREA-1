<?php

namespace App\Http\Controllers;

use App\Models\BitacoraUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpleadoController extends Controller
{
    protected function cargarDashboardData()
    {
        $usuarioId = session('usuario_id') ?? (Auth::check() ? Auth::id() : null);
        $bitacora = [];

        if ($usuarioId) {
            $bitacora = BitacoraUsuario::where('usuario_id', $usuarioId)
                ->orderByDesc('fecha')
                ->limit(20)
                ->get();
        }

        return compact('bitacora');
    }

    public function dashboard()
    {
        return view('employer.dashboard-employee', $this->cargarDashboardData());
    }

    public function libros()
    {
        return view('employer.dashboard-employee', $this->cargarDashboardData());
    }
}