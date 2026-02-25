<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\LibroController;

// Rutas públicas
Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.procesar');

// Rutas protegidas
Route::middleware('auth.custom')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard Administrador
    Route::middleware('rol:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/libros', [AdminController::class, 'libros'])->name('admin.libros');
        Route::get('/admin/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
        Route::get('/admin/formatos', [AdminController::class, 'formatos'])->name('admin.formatos');
        
        // Rutas para gestión de empleados
        Route::post('/admin/empleado/crear', [AdminController::class, 'crearEmpleado'])->name('admin.empleado.crear');
        Route::post('/admin/empleado/eliminar/{id}', [AdminController::class, 'eliminarEmpleado'])->name('admin.empleado.eliminar');
        
        // Rutas para gestión de libros (Admin)
        Route::post('/admin/libro/crear', [LibroController::class, 'crear'])->name('admin.libro.crear');
        Route::post('/admin/libro/eliminar/{id}', [LibroController::class, 'eliminar'])->name('admin.libro.eliminar');
        Route::post('/admin/libro/actualizar/{id}', [LibroController::class, 'actualizar'])->name('admin.libro.actualizar');
    });
    
    // Dashboard Empleado
    Route::middleware('rol:empleado')->group(function () {
        Route::get('/empleado/dashboard', [EmpleadoController::class, 'dashboard'])->name('empleado.dashboard');
        Route::get('/empleado/libros', [EmpleadoController::class, 'libros'])->name('empleado.libros');
        
        // Rutas para gestión de libros (Empleado)
        Route::post('/empleado/libro/crear', [LibroController::class, 'crear'])->name('empleado.libro.crear');
        Route::post('/empleado/libro/eliminar/{id}', [LibroController::class, 'eliminar'])->name('empleado.libro.eliminar');
        Route::post('/empleado/libro/actualizar/{id}', [LibroController::class, 'actualizar'])->name('empleado.libro.actualizar');
    });
});
