<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\LibroController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (ACCESO LIBRE)
|--------------------------------------------------------------------------
*/

// 1. PÁGINA DE INICIO (La de INMERSIA)
Route::get('/', function () {
    return view('welcome');
})->name('inicio');

// 2. RUTAS QUE PIDE TU DISEÑO (Para evitar errores)
Route::get('/tienda', function () {
    return "Pantalla de Tienda en construcción";
})->name('storebook');

Route::get('/libros/{tipo}', function ($tipo) {
    return "Filtro de libros: " . $tipo;
})->name('libros.tipo');

// 3. LOGIN
Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.procesar');

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (REQUIEREN LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth.custom')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // ÁREA DE ADMINISTRADOR
    Route::middleware('rol:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/libros', [AdminController::class, 'libros'])->name('admin.libros');
        Route::get('/admin/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
        Route::get('/admin/formatos', [AdminController::class, 'formatos'])->name('admin.formatos');
        
        Route::post('/admin/empleado/crear', [AdminController::class, 'crearEmpleado'])->name('admin.empleado.crear');
        Route::post('/admin/empleado/eliminar/{id}', [AdminController::class, 'eliminarEmpleado'])->name('admin.empleado.eliminar');
        
        Route::post('/admin/libro/crear', [LibroController::class, 'crear'])->name('admin.libro.crear');
        Route::post('/admin/libro/eliminar/{id}', [LibroController::class, 'eliminar'])->name('admin.libro.eliminar');
        Route::post('/admin/libro/actualizar/{id}', [LibroController::class, 'actualizar'])->name('admin.libro.actualizar');
    });
    
    // ÁREA DE EMPLEADO
    Route::middleware('rol:empleado')->group(function () {
        Route::get('/empleado/dashboard', [EmpleadoController::class, 'dashboard'])->name('empleado.dashboard');
        Route::get('/empleado/libros', [EmpleadoController::class, 'libros'])->name('empleado.libros');
        
        Route::post('/empleado/libro/crear', [LibroController::class, 'crear'])->name('empleado.libro.crear');
        Route::post('/empleado/libro/eliminar/{id}', [LibroController::class, 'eliminar'])->name('empleado.libro.eliminar');
        Route::post('/empleado/libro/actualizar/{id}', [LibroController::class, 'actualizar'])->name('empleado.libro.actualizar');
    });
});