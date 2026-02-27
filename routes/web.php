<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\ClienteController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

// Página principal
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Alias adicional si necesitas route('inicio')
Route::get('/inicio', function () {
    return view('welcome');
})->name('inicio');

// Tienda y Contacto
Route::get('/tienda', function () {
    return "Tienda en construcción";
})->name('storebook');

Route::get('/contacto', function () {
    return "Contacto en construcción";
})->name('contact');

Route::get('/libros/{tipo}', function ($tipo) {
    return "Filtro de libros: " . $tipo;
})->name('libros.tipo');


/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

// LOGIN
Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.procesar');

// Si en algún lugar usas route('mostrarLogin')
Route::get('/mostrar-login', [AuthController::class, 'mostrarLogin'])->name('mostrarLogin');


// REGISTRO
Route::get('/register', [AuthController::class, 'mostrarRegistro'])->name('register');
Route::post('/register', [AuthController::class, 'registrar'])->name('registrar');

// Si en algún lugar usas route('mostrarRegistro')
Route::get('/mostrar-registro', [AuthController::class, 'mostrarRegistro'])->name('mostrarRegistro');


/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/

Route::middleware('auth.custom')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | ÁREA CLIENTE
    |--------------------------------------------------------------------------
    */
    Route::middleware('rol:cliente')->group(function () {
        Route::get('/dashboard', [ClienteController::class, 'index'])->name('client.dashboard');
    });

    /*
    |--------------------------------------------------------------------------
    | ÁREA ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('rol:admin')->group(function () {

        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/libros', [AdminController::class, 'libros'])->name('admin.libros');
        Route::get('/admin/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
        Route::get('/admin/formatos', [AdminController::class, 'formatos'])->name('admin.formatos');

        Route::post('/admin/empleado/crear', [AdminController::class, 'crearEmpleado'])->name('admin.empleado.crear');

        Route::post('/admin/libro/crear', [LibroController::class, 'crear'])->name('admin.libro.crear');
        Route::post('/admin/libro/eliminar/{id}', [LibroController::class, 'eliminar'])->name('admin.libro.eliminar');
        Route::post('/admin/libro/actualizar/{id}', [LibroController::class, 'actualizar'])->name('admin.libro.actualizar');
    });

    /*
    |--------------------------------------------------------------------------
    | ÁREA EMPLEADO
    |--------------------------------------------------------------------------
    */
    Route::middleware('rol:empleado')->group(function () {
        Route::get('/empleado/dashboard', [EmpleadoController::class, 'dashboard'])->name('empleado.dashboard');
    });

});