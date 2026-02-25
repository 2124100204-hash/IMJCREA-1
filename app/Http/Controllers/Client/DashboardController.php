<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Libro;

class DashboardController extends Controller
{
    public function index()
    {
        $libros = Libro::all(); // o los que quieras mostrar

        return view('client.dashboard-client', compact('libros'));
    }
}