<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\Auth\LoginController;
use Laravel\Socialite\Facades\Socialite;
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

/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

// LOGIN
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('login.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// REGISTRO
Route::get('/register', [AuthController::class, 'mostrarRegistro'])->name('register');
Route::post('/register', [AuthController::class, 'registrar'])->name('registrar');

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth.custom'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Alias adicional si necesitas route('inicio')
    Route::get('/inicio', function () {
        return view('welcome');
    })->name('inicio');

    /*
    |--------------------------------------------------------------------------
    | ÁREA CLIENTE
    |--------------------------------------------------------------------------
    */
    Route::middleware('rol:cliente')->group(function () {
        Route::get('/dashboard', [ClienteController::class, 'index'])->name('client.dashboard');
        Route::post('/cliente/perfil/actualizar', [AuthController::class, 'actualizarPerfil'])->name('cliente.perfil.actualizar');

        // Tienda autenticada para cliente
        Route::get('/cliente/tienda', [LibroController::class, 'index'])->name('cliente.storebook');
        Route::get('/cliente/libros/{tipo}', [LibroController::class, 'tipo'])->name('cliente.libros.tipo');

        // Detalles de libro para cliente
        Route::get('/cliente/libro/{id}', [LibroController::class, 'show'])->name('cliente.libro.details');

        // Rutas de favoritos
        Route::post('/favorito/agregar', [FavoritoController::class, 'agregar'])->name('favorito.agregar');
        Route::post('/favorito/eliminar', [FavoritoController::class, 'eliminar'])->name('favorito.eliminar');
        Route::get('/favoritos', [FavoritoController::class, 'obtener'])->name('favoritos.obtener');

        // Rutas de compras y devoluciones
        Route::middleware(['circuit_breaker', 'idempotency'])->group(function () {
            Route::post('/comprar', [PedidoController::class, 'comprar'])->name('pedido.comprar');
            Route::post('/procesar-compra', [PedidoController::class, 'procesarCompraCarrito'])->name('pedido.procesar.compra');
            Route::post('/devolver', [PedidoController::class, 'devolver'])->name('pedido.devolver');
        });
        Route::get('/mis-compras', [PedidoController::class, 'getComprasUsuario'])->name('pedido.compras');
    });

    /*
    |--------------------------------------------------------------------------
    | ÁREA ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware(['rol:admin', 'circuit_breaker'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/libros', [AdminController::class, 'libros'])->name('admin.libros');
        Route::get('/admin/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
        Route::get('/admin/formatos', [AdminController::class, 'formatos'])->name('admin.formatos');

        Route::post('/admin/autor/crear', [AdminController::class, 'crearAutor'])->name('admin.autor.crear');
        Route::post('/admin/categoria/crear', [AdminController::class, 'crearCategoria'])->name('admin.categoria.crear');

        // Gestión de empleados
        Route::post('/admin/empleado/crear', [AdminController::class, 'crearEmpleado'])->name('admin.empleado.crear');
        Route::post('/admin/empleado/eliminar/{id}', [AdminController::class, 'eliminarEmpleado'])->name('admin.empleado.eliminar');

        // Gestión de libros
        Route::post('/admin/libro/crear', [AdminController::class, 'crearLibro'])->name('admin.libro.crear');
        Route::post('/admin/libro/eliminar/{id}', [LibroController::class, 'eliminar'])->name('admin.libro.eliminar');
        Route::post('/admin/libro/actualizar/{id}', [AdminController::class, 'actualizarLibro'])->name('admin.libro.actualizar');

        // Gestión de devoluciones
        Route::get('/admin/devoluciones', [AdminController::class, 'devoluciones'])->name('admin.devoluciones');
        Route::post('/admin/devolucion/procesar/{id}', [AdminController::class, 'procesarDevolucion'])->name('admin.devolucion.procesar');

        // Gestión de pedidos
        Route::post('/admin/pedido/{pedidoId}/estado', [AdminController::class, 'actualizarEstadoPedido'])->name('admin.pedido.estado');
    });

    /*
    |--------------------------------------------------------------------------
    | ÁREA EMPLEADO
    |--------------------------------------------------------------------------
    */
    Route::middleware('rol:empleado')->group(function () {
        Route::get('/empleado/dashboard', [EmpleadoController::class, 'dashboard'])->name('empleado.dashboard');
        Route::get('/empleado/libros', [EmpleadoController::class, 'libros'])->name('empleado.libros');

        // Gestión de libros
        Route::post('/empleado/libro/crear', [LibroController::class, 'crear'])->name('empleado.libro.crear');
        Route::post('/empleado/libro/eliminar/{id}', [LibroController::class, 'eliminar'])->name('empleado.libro.eliminar');
        Route::post('/empleado/libro/actualizar/{id}', [LibroController::class, 'actualizar'])->name('empleado.libro.actualizar');
    });
});