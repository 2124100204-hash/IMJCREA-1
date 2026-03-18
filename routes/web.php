<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FavoritoController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/inicio', function () {
    return view('welcome');
})->name('inicio');

// ✅ Contacto — una sola definición con controlador
Route::get('/contact',  [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Tienda pública
Route::get('/tienda', [LibroController::class, 'index'])->name('storebook');
Route::get('/libros/{tipo}', [LibroController::class, 'tipo'])->name('libros.tipo');
Route::get('/libro/{id}', [LibroController::class, 'show'])->name('libro.details');

/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

Route::get('/login',         [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/login',        [AuthController::class, 'login'])->name('login.procesar');
Route::get('/mostrar-login', [AuthController::class, 'mostrarLogin'])->name('mostrarLogin');

Route::get('/register',          [AuthController::class, 'mostrarRegistro'])->name('register');
Route::post('/register',         [AuthController::class, 'registrar'])->name('registrar');
Route::get('/mostrar-registro',  [AuthController::class, 'mostrarRegistro'])->name('mostrarRegistro');

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/

Route::middleware('auth.custom')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | ÁREA ADMIN
    |--------------------------------------------------------------------------
    */
Route::middleware('rol:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Libros
    Route::post('/admin/libro/crear', [LibroController::class, 'crear'])->name('admin.libro.crear');
    Route::delete('/admin/libro/eliminar/{id}', [LibroController::class, 'eliminar'])->name('admin.libro.eliminar');
    Route::put('/admin/libro/actualizar/{id}', [LibroController::class, 'actualizar'])->name('admin.libro.actualizar');

    // Autores 
    Route::post('/admin/autor/crear', [AdminController::class, 'crearAutor'])->name('admin.autor.crear');

    // Empleados
  Route::post('/admin/empleado/crear',            [EmpleadoController::class, 'crear'])->name('admin.empleado.crear');
Route::put('/admin/empleado/actualizar/{id}',   [EmpleadoController::class, 'actualizar'])->name('admin.empleado.actualizar');
Route::delete('/admin/empleado/eliminar/{id}',  [EmpleadoController::class, 'eliminar'])->name('admin.empleado.eliminar');
});
    /*
    |--------------------------------------------------------------------------
    | ÁREA EMPLEADO
    |--------------------------------------------------------------------------
    */
    Route::middleware('rol:empleado')->group(function () {

        Route::get('/empleado/dashboard', [EmpleadoController::class, 'dashboard'])->name('empleado.dashboard');
        Route::get('/empleado/libros',    [EmpleadoController::class, 'libros'])->name('empleado.libros');

        Route::post('/empleado/libro/crear',           [LibroController::class, 'crear'])->name('empleado.libro.crear');
        Route::post('/empleado/libro/eliminar/{id}',   [LibroController::class, 'eliminar'])->name('empleado.libro.eliminar');
        Route::post('/empleado/libro/actualizar/{id}', [LibroController::class, 'actualizar'])->name('empleado.libro.actualizar');
    });

    /*
    |--------------------------------------------------------------------------
    | ÁREA CLIENTE
    |--------------------------------------------------------------------------
    */
    Route::middleware('rol:cliente')->group(function () {

        Route::get('/dashboard',         [ClienteController::class, 'index'])->name('client.dashboard');
        Route::get('/cliente/dashboard', [ClienteController::class, 'dashboard'])->name('cliente.dashboard');

        Route::get('/cliente/tienda',        [LibroController::class, 'index'])->name('cliente.storebook');
        Route::get('/cliente/libros/{tipo}', [LibroController::class, 'tipo'])->name('cliente.libros.tipo');
        Route::get('/cliente/libro/{id}',    [LibroController::class, 'show'])->name('cliente.libro.details');

        Route::post('/favorito/agregar',  [FavoritoController::class, 'agregar'])->name('favorito.agregar');
        Route::post('/favorito/eliminar', [FavoritoController::class, 'eliminar'])->name('favorito.eliminar');
        Route::get('/favoritos',          [FavoritoController::class, 'obtener'])->name('favoritos.obtener');
    });

});