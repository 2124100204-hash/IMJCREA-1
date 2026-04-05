<?php

namespace App\Http\Controllers;

use App\Models\LibroFormato;
use App\Models\Venta;
use App\Models\Usuario;
use App\Models\Autor;
use App\Models\Libro;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function crearLibro(Request $request)
    {
        try {
            // 1. Manejo del Autor (Si el usuario escribió uno nuevo en el otro modal o si viene del select)
            $autorId = $request->autor_id;

            // 2. Manejo de Categoría (Ignorando Mayúsculas/Minúsculas)
            $categoriaId = null;
            if ($request->filled('categoria_nombre')) {
                $nombreCat = trim($request->categoria_nombre);
                // Buscamos la categoría sin importar A/a
                $categoria = \App\Models\Categoria::whereRaw('LOWER(nombre) = ?', [strtolower($nombreCat)])->first();

                if (!$categoria) {
                    $categoria = \App\Models\Categoria::create(['nombre' => $nombreCat]);
                }
                $categoriaId = $categoria->id;
            }

            // 3. Crear el Libro
            $libro = new \App\Models\Libro();
            $libro->titulo = $request->titulo;
            $libro->autor_id = $autorId;
            $libro->categoria_id = $categoriaId;
            $libro->descripcion = $request->descripcion;
            $libro->nivel_edad = $request->nivel_edad;
            $libro->duracion = $request->duracion;

            // Subida de imagen
            if ($request->hasFile('portada')) {
                $libro->portada = $request->file('portada')->store('portadas', 'public');
            }

            $libro->save(); // 🚩 CRÍTICO: Guardar aquí para generar el ID del libro

            // 4. Crear los registros en 'libro_formatos'
            if ($request->has('formatos')) {
                foreach ($request->formatos as $tipo => $datos) {
                    // Solo si el checkbox 'activo' fue marcado
                    if (isset($datos['activo'])) {
                        \App\Models\LibroFormato::create([
                            'libro_id' => $libro->id,
                            'formato'  => $tipo, // 'fisico', 'ar', 'vr'
                            'stock'    => $datos['stock'] ?? 0,
                            'precio'   => $datos['precio'] ?? 0,
                        ]);
                    }
                }
            }

            return back()->with('success', '¡Libro y formatos registrados exitosamente!');

        } catch (\Exception $e) {
            // Si hay un error de SQL (columna faltante, etc), esto lo atrapará
            return back()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function dashboard()
    {
        // Obtener pedidos con sus relaciones
        $pedidos = \App\Models\Pedido::with([
            'usuario',
            'detalles.libro'
        ])->orderBy('created_at', 'desc')->get();
        
        $ventas = Venta::orderBy('created_at', 'desc')->get();
        $devoluciones = \App\Models\Devolucion::with([
            'pedidoDetalle.pedido.usuario',
            'pedidoDetalle.libro'
        ])->orderBy('created_at', 'desc')->get();

        return view('employer.dashboard-admin', compact('ventas', 'devoluciones', 'pedidos'));
    }

    public function actualizarEstadoPedido(Request $request, $pedidoId)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,confirmado,enviado,entregado,cancelado'
        ]);

        $pedido = \App\Models\Pedido::findOrFail($pedidoId);
        $pedido->update(['estado' => $request->estado]);

        return response()->json([
            'success' => true,
            'message' => 'Estado del pedido actualizado correctamente',
            'estado' => $pedido->estado
        ]);
    }

    public function libros()
    {
        return view('employer.dashboard-admin');
    }

    public function usuarios()
    {
        return view('employer.dashboard-admin');
    }

    public function formatos()
    {
        return view('employer.dashboard-admin');
    }

    public function crearAutor(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        $nombre = trim($request->nombre);
        $existe = Autor::whereRaw('LOWER(nombre) = ?', [strtolower($nombre)])->exists();

        if ($existe) {
            return back()->withErrors(['nombre' => 'El autor ya existe (sin diferenciar mayúsculas/minúsculas).'])->withInput();
        }

        Autor::create(['nombre' => $nombre]);

        return back()->with('success', 'Autor añadido correctamente.');
    }

    public function crearCategoria(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        $nombre = trim($request->nombre);
        $existe = Categoria::whereRaw('LOWER(nombre) = ?', [strtolower($nombre)])->exists();

        if ($existe) {
            return back()->withErrors(['nombre' => 'La categoría ya existe (sin diferenciar mayúsculas/minúsculas).'])->withInput();
        }

        Categoria::create(['nombre' => $nombre]);

        return back()->with('success', 'Categoría añadida correctamente.');
    }

    public function crearEmpleado(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:8|confirmed',
            'puesto' => 'required|string|max:255',
            'departamento' => 'nullable|string|max:255',
            'salario' => 'nullable|numeric|min:0',
            'telefono' => 'nullable|string|max:20',
            'curp' => 'nullable|string|max:18',
            'domicilio' => 'nullable|string|max:500'
        ]);

        // Sanitizar teléfono
        $telefono = $request->telefono;
        if ($telefono) {
            $telefono = preg_replace('/[^\d\s\-\(\)\+]/', '', $telefono);
            $telefono = trim($telefono);
        }

        // Crear el usuario primero
        $usuario = Usuario::create([
            'username' => $request->email, // Usar email como username
            'email' => $request->email,
            'nombre' => $request->nombre,
            'password' => Hash::make($request->password),
            'rol' => 'empleado',
            'activo' => true
        ]);

        // Crear el empleado
        Empleado::create([
            'usuario_id' => $usuario->id,
            'nombre' => $request->nombre,
            'puesto' => $request->puesto,
            'departamento' => $request->departamento,
            'salario' => $request->salario,
            'telefono' => $telefono,
            'curp' => $request->curp,
            'domicilio' => $request->domicilio
        ]);

        return back()->with('success', 'Empleado creado correctamente');
    }

    public function actualizarLibro(Request $request, $id)
    {
        try {
            $libro = Libro::findOrFail($id);

            // 1. Manejo del Autor (Si el usuario cambió el autor)
            $autorId = $request->autor_id;
            if (!$autorId) {
                return back()->withErrors(['autor_id' => 'Debe seleccionar un autor válido.']);
            }

            // 2. Manejo de Categoría (Ignorando Mayúsculas/Minúsculas)
            $categoriaId = null;
            if ($request->filled('categoria_nombre')) {
                $nombreCat = trim($request->categoria_nombre);
                // Buscamos la categoría sin importar A/a
                $categoria = \App\Models\Categoria::whereRaw('LOWER(nombre) = ?', [strtolower($nombreCat)])->first();

                if (!$categoria) {
                    $categoria = \App\Models\Categoria::create(['nombre' => $nombreCat]);
                }
                $categoriaId = $categoria->id;
            }

            // 3. Actualizar el Libro
            $libro->titulo = $request->titulo;
            $libro->autor_id = $autorId;
            $libro->categoria_id = $categoriaId;
            $libro->descripcion = $request->descripcion;
            $libro->nivel_edad = $request->nivel_edad ?? $libro->nivel_edad;
            $libro->duracion = $request->duracion ?? $libro->duracion;

            // Subida de imagen si se proporciona
            if ($request->hasFile('portada')) {
                $libro->portada = $request->file('portada')->store('portadas', 'public');
            }

            $libro->save();

            // 4. Actualizar los registros en 'libro_formatos'
            if ($request->has('formatos')) {
                // Primero eliminamos los formatos existentes
                \App\Models\LibroFormato::where('libro_id', $libro->id)->delete();

                // Luego creamos los nuevos
                foreach ($request->formatos as $tipo => $datos) {
                    // Solo si el checkbox 'activo' fue marcado
                    if (isset($datos['activo'])) {
                        \App\Models\LibroFormato::create([
                            'libro_id' => $libro->id,
                            'formato'  => $tipo, // 'fisico', 'ar', 'vr'
                            'stock'    => $datos['stock'] ?? 0,
                            'precio'   => $datos['precio'] ?? 0,
                        ]);
                    }
                }
            }

            return back()->with('success', '¡Libro actualizado exitosamente!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function devoluciones()
    {
        $devoluciones = \App\Models\Devolucion::with([
            'pedidoDetalle.pedido.usuario',
            'pedidoDetalle.libro'
        ])->orderBy('created_at', 'desc')->get();

        return view('employer.dashboard-admin', compact('devoluciones'));
    }

    public function procesarDevolucion(Request $request, $devolucionId)
    {
        $request->validate([
            'accion' => 'required|in:aprobar,rechazar',
        ]);

        $devolucion = \App\Models\Devolucion::with('pedidoDetalle.libro')->findOrFail($devolucionId);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $devolucion) {
            if ($request->accion === 'aprobar') {
                // Aumentar stock
                $formato = \App\Models\LibroFormato::where('libro_id', $devolucion->pedidoDetalle->libro_id)
                    ->where('formato', $devolucion->pedidoDetalle->formato)
                    ->first();

                if ($formato) {
                    $formato->increment('stock', $devolucion->cantidad_devuelta);
                }

                $devolucion->update(['estado' => 'procesada']);
            } else {
                $devolucion->update(['estado' => 'rechazada']);
            }
        });

        return back()->with('success', 'Devolución procesada correctamente');
    }
}