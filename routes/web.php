<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\LibroController;
use App\Models\Libro;


Route::get('/', function () {
    return view('welcome');
});

// =======================
// ruta tienda de libro
// =======================
Route::get('/storebook', function () {
    $libros = \App\Models\Libro::with('formatos')->get();
    return view('storebook', compact('libros'));
})->name('storebook');

Route::get('/storebook/{tipo}', function ($tipo) {
    $libros = \App\Models\Libro::whereHas('formatos', function ($query) use ($tipo) {
        $query->where('formato', $tipo)
              ->where('stock', '>', 0);
    })->with(['formatos' => function ($query) use ($tipo) {
        $query->where('formato', $tipo);
    }])->get();

    return view('storebook', compact('libros'));
})->name('libros.tipo');

// =======================
// ruta inicio
// =======================
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// =======================
// ruta contacto
// =======================
Route::get('/contact', function () {
    return view('contact');
})->name('contact');


// =======================
// LOGIN Y REGISTRO
// =======================
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// 👇 ESTA ES LA RUTA POST QUE FALTABA Y CAUSABA EL ERROR 👇
Route::post('/register', function (Request $request) {
    $request->validate([
        'codigo'   => 'required|unique:usuarios,codigo',
        'password' => 'required|min:4', 
    ]);

    try {
        Usuario::create([
            'codigo'       => $request->codigo,
            'password'     => Hash::make($request->password),
            'tipo_usuario' => 'cliente' 
        ]);
        
        return redirect()->route('login')->with('success', '¡Registrado con éxito!');
    } catch (\Exception $e) {
        return back()->withErrors(['db_error' => 'Error al guardar: ' . $e->getMessage()]);
    }
});
// 👆 -------------------------------------------------- 👆

Route::get('/login', function () {
    return view('auth.login');
})->name('login');


Route::post('/login', function (Request $request) {

    $request->validate([
        'codigo' => 'required',
        'password' => 'required'
    ]);

    $usuario = Usuario::where('codigo', $request->codigo)->first();


    if (!$usuario || !Hash::check($request->password, $usuario->password)) {
        return back()->with('error', 'Credenciales incorrectas');
    }

    Session::put('usuario', $usuario);

    if ($usuario->tipo_usuario === 'admin') {
        return redirect()->route('dashboard.dashboard-admin');
    }

    if ($usuario->tipo_usuario === 'empleado') {
        return redirect()->route('dashboard.employee');
    }

    return redirect()->route('dashboard.dashboard-client');
});

Route::post('/admin/empleado/crear', function (Request $request) {

    if (!Session::has('usuario') || Session::get('usuario')->tipo_usuario !== 'admin') {
        return redirect()->route('login');
    }

    $request->validate([
        'codigo' => 'required|unique:usuarios,codigo',
        'password' => 'required|min:4'
    ]);

    Usuario::create([
        'codigo' => $request->codigo,
        'password' => Hash::make($request->password),
        'tipo_usuario' => 'empleado'
    ]);

    return back()->with('success', 'Empleado creado correctamente');
})->name('admin.empleado.crear');


Route::post('/admin/empleado/eliminar/{id}', function ($id) {

    if (!Session::has('usuario') || Session::get('usuario')->tipo_usuario !== 'admin') {
        return redirect()->route('login');
    }

    Usuario::where('id', $id)->where('tipo_usuario', 'empleado')->delete();

    return back()->with('success', 'Empleado eliminado');
})->name('admin.empleado.eliminar');

// =======================
// LOGOUT
// =======================
Route::post('/logout', function () {
    Session::forget('usuario');
    return redirect()->route('login');
})->name('logout');


// =======================
// DASHBOARDS PROTEGIDOS
// =======================

Route::get('/dashboard/admin', function () {
    if (!Session::has('usuario') || Session::get('usuario')->tipo_usuario !== 'admin') {
        return redirect()->route('login');
    }
    return view('employer.dashboard-admin');
})->name('dashboard.dashboard-admin');


Route::get('/dashboard/employee', function () {
    if (!Session::has('usuario') || Session::get('usuario')->tipo_usuario !== 'empleado') {
        return redirect()->route('login');
    }
    return view('employer.employee');
})->name('dashboard.employee');


Route::get('/dashboard/client', function () {
    if (!Session::has('usuario') || Session::get('usuario')->tipo_usuario !== 'cliente') {
        return redirect()->route('login');
    }
    return view('client.dashboard-client');
})->name('dashboard.dashboard-client');