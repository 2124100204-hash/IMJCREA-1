<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function dashboard()
    {
        return view('employer.dashboard-employee');
    }

    public function libros()
    {
        return view('employer.dashboard-employee');
    }
}