<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\LibroController;

use App\Http\Controllers\FavoritoController;


/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::get('/contact', function(){
    return view('contact');
})->name('contact');    
// Rutas públicas de login
Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.procesar');

// Ruta para la tienda (storebook)
Route::get('/tienda', [LibroController::class, 'index'])->name('storebook');

// Rutas para filtrar libros por tipo (AR o VR)
Route::get('/libros/{tipo}', [LibroController::class, 'tipo'])->name('libros.tipo');

// Ruta pública para ver detalles de un libro
Route::get('/libro/{id}', [LibroController::class, 'show'])->name('libro.details');

// Rutas protegidas

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

    // Dashboard Administrador

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


        // Gestión de empleados
        Route::post('/admin/empleado/crear', [AdminController::class, 'crearEmpleado'])->name('admin.empleado.crear');
        Route::post('/admin/empleado/eliminar/{id}', [AdminController::class, 'eliminarEmpleado'])->name('admin.empleado.eliminar');

        // Gestión de libros (Admin)

        Route::post('/admin/empleado/crear', [AdminController::class, 'crearEmpleado'])->name('admin.empleado.crear');

        Route::post('/admin/libro/crear', [LibroController::class, 'crear'])->name('admin.libro.crear');
        Route::post('/admin/libro/eliminar/{id}', [LibroController::class, 'eliminar'])->name('admin.libro.eliminar');
        Route::post('/admin/libro/actualizar/{id}', [LibroController::class, 'actualizar'])->name('admin.libro.actualizar');
    });


    // Dashboard Empleado
    Route::middleware('rol:empleado')->group(function () {
        Route::get('/empleado/dashboard', [EmpleadoController::class, 'dashboard'])->name('empleado.dashboard');
        Route::get('/empleado/libros', [EmpleadoController::class, 'libros'])->name('empleado.libros');

        // Gestión de libros (Empleado)
        Route::post('/empleado/libro/crear', [LibroController::class, 'crear'])->name('empleado.libro.crear');
        Route::post('/empleado/libro/eliminar/{id}', [LibroController::class, 'eliminar'])->name('empleado.libro.eliminar');
        Route::post('/empleado/libro/actualizar/{id}', [LibroController::class, 'actualizar'])->name('empleado.libro.actualizar');
    });

    // Dashboard Cliente
    Route::middleware('rol:cliente')->group(function () {
        Route::get('/cliente/dashboard', [ClienteController::class, 'dashboard'])->name('cliente.dashboard');
        
        // Tienda autenticada para cliente
        Route::get('/cliente/tienda', [LibroController::class, 'index'])->name('cliente.storebook');
        Route::get('/cliente/libros/{tipo}', [LibroController::class, 'tipo'])->name('cliente.libros.tipo');
        
        // Detalles de libro para cliente
        Route::get('/cliente/libro/{id}', [LibroController::class, 'show'])->name('cliente.libro.details');
        
        // Rutas de favoritos
        Route::post('/favorito/agregar', [FavoritoController::class, 'agregar'])->name('favorito.agregar');
        Route::post('/favorito/eliminar', [FavoritoController::class, 'eliminar'])->name('favorito.eliminar');
        Route::get('/favoritos', [FavoritoController::class, 'obtener'])->name('favoritos.obtener');
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