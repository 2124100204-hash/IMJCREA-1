<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\LibroController;
use App\Models\Libro;
use App\Http\Controllers\Client\DashboardController;



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


Route::middleware(['usuario:admin'])->group(function () {

    Route::get('/dashboard/admin', function () {
        return view('employer.dashboard-admin');
    })->name('dashboard.dashboard-admin');

    Route::post('/admin/empleado/crear', function (Request $request) {

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

        Usuario::where('id', $id)
            ->where('tipo_usuario', 'empleado')
            ->delete();

        return back()->with('success', 'Empleado eliminado');
    })->name('admin.empleado.eliminar');
});
Route::middleware(['usuario:empleado'])->group(function () {

    Route::get('/dashboard/employee', function () {
        return view('employer.employee');
    })->name('dashboard.employee');

});
Route::middleware(['usuario:cliente'])->group(function () {

    Route::get('/dashboard/client', [DashboardController::class, 'index'])
        ->name('client.dashboard');

});

Route::post('/logout', function () {
    Session::forget('usuario');
    return redirect()->route('login');
})->middleware('usuario')->name('logout');

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

    return match ($usuario->tipo_usuario) {
        'admin' => redirect()->route('dashboard.dashboard-admin'),
        'empleado' => redirect()->route('dashboard.employee'),
        'cliente' => redirect()->route('client.dashboard'),
        default => redirect()->route('login')
    };

})->name('login.post');


Route::get('/', fn() => view('welcome'));
Route::get('/welcome', fn() => view('welcome'))->name('welcome');
Route::get('/contact', fn() => view('contact'))->name('contact');
Route::get('/register', fn() => view('auth.register'))->name('register');
