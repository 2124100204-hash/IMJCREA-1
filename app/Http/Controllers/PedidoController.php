<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\Devolucion;
use App\Models\LibroFormato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PedidoController extends Controller
{
    public function comprar(Request $request)
    {
        $request->validate([
            'libro_id' => 'required|exists:libros,id',
            'formato' => 'required|in:fisico,ar,vr',
            'cantidad' => 'required|integer|min:1',
        ]);

        $usuario = Auth::user();

        // Verificar stock
        $formato = LibroFormato::where('libro_id', $request->libro_id)
            ->where('formato', $request->formato)
            ->first();

        if (!$formato || $formato->stock < $request->cantidad) {
            throw ValidationException::withMessages([
                'stock' => 'Stock insuficiente para este formato.'
            ]);
        }

        DB::transaction(function () use ($request, $usuario, $formato) {
            // Crear pedido
            $pedido = Pedido::create([
                'usuario_id' => $usuario->id,
                'estado' => 'pendiente',
                'total' => $formato->precio * $request->cantidad,
            ]);

            // Crear detalle
            PedidoDetalle::create([
                'pedido_id' => $pedido->id,
                'libro_id' => $request->libro_id,
                'cantidad' => $request->cantidad,
                'precio_unitario' => $formato->precio,
                'estado' => 'pendiente',
                'formato' => $request->formato,
            ]);

            // Reducir stock
            $formato->decrement('stock', $request->cantidad);
        });

        return response()->json(['success' => true, 'message' => 'Compra realizada exitosamente']);
    }

    public function devolver(Request $request)
    {
        $request->validate([
            'pedido_detalle_id' => 'required|exists:pedido_detalles,id',
            'cantidad_devuelta' => 'required|integer|min:1',
            'razon' => 'nullable|string|max:500',
        ]);

        $usuario = Auth::user();
        $detalle = PedidoDetalle::with('pedido')->find($request->pedido_detalle_id);

        // Verificar que el pedido pertenece al usuario
        if ($detalle->pedido->usuario_id !== $usuario->id) {
            throw ValidationException::withMessages([
                'autorizacion' => 'No tienes permiso para devolver este producto.'
            ]);
        }

        // Verificar estado del pedido
        if ($detalle->pedido->estado !== 'entregado') {
            throw ValidationException::withMessages([
                'estado' => 'Solo puedes devolver productos de pedidos entregados.'
            ]);
        }

        // Verificar que no haya sido devuelto ya
        if ($detalle->estado === 'devuelto') {
            throw ValidationException::withMessages([
                'estado' => 'Este producto ya ha sido devuelto.'
            ]);
        }

        // Verificar cantidad
        $cantidadYaDevuelta = $detalle->devoluciones()->whereIn('estado', ['solicitada', 'aprobada', 'procesada'])->sum('cantidad_devuelta');
        $cantidadDisponible = $detalle->cantidad - $cantidadYaDevuelta;

        if ($request->cantidad_devuelta > $cantidadDisponible) {
            throw ValidationException::withMessages([
                'cantidad' => "Solo puedes devolver {$cantidadDisponible} unidades más."
            ]);
        }

        DB::transaction(function () use ($request, $detalle) {
            // Crear devolución
            $montoReembolsado = $detalle->precio_unitario * $request->cantidad_devuelta;

            Devolucion::create([
                'pedido_detalle_id' => $detalle->id,
                'cantidad_devuelta' => $request->cantidad_devuelta,
                'monto_reembolsado' => $montoReembolsado,
                'razon' => $request->razon,
                'estado' => 'solicitada',
            ]);

            // Si se devuelve todo, marcar como devuelto
            $totalDevuelto = $detalle->devoluciones()->sum('cantidad_devuelta') + $request->cantidad_devuelta;
            if ($totalDevuelto >= $detalle->cantidad) {
                $detalle->update(['estado' => 'devuelto']);
            }
        });

        return response()->json(['success' => true, 'message' => 'Solicitud de devolución enviada']);
    }

    public function procesarDevolucion(Request $request, $devolucionId)
    {
        $request->validate([
            'accion' => 'required|in:aprobar,rechazar',
        ]);

        $devolucion = Devolucion::with('pedidoDetalle.libro')->findOrFail($devolucionId);

        DB::transaction(function () use ($request, $devolucion) {
            if ($request->accion === 'aprobar') {
                // Aumentar stock
                $formato = LibroFormato::where('libro_id', $devolucion->pedidoDetalle->libro_id)
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

        return response()->json(['success' => true, 'message' => 'Devolución procesada']);
    }

    public function getComprasUsuario()
    {
        $usuario = Auth::user();

        $pedidos = Pedido::where('usuario_id', $usuario->id)
            ->with(['detalles.libro', 'detalles.devoluciones'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Convertir a JSON para el usuario
        $comprasJson = $pedidos->map(function ($pedido) {
            return [
                'id' => $pedido->id,
                'estado' => $pedido->estado,
                'total' => $pedido->total,
                'fecha' => $pedido->created_at->format('Y-m-d H:i:s'),
                'productos' => $pedido->detalles->map(function ($detalle) {
                    return [
                        'libro_id' => $detalle->libro_id,
                        'titulo' => $detalle->libro->titulo,
                        'formato' => $detalle->formato,
                        'cantidad' => $detalle->cantidad,
                        'precio_unitario' => $detalle->precio_unitario,
                        'estado' => $detalle->estado,
                        'devoluciones' => $detalle->devoluciones->map(function ($devolucion) {
                            return [
                                'cantidad_devuelta' => $devolucion->cantidad_devuelta,
                                'monto_reembolsado' => $devolucion->monto_reembolsado,
                                'estado' => $devolucion->estado,
                                'razon' => $devolucion->razon,
                            ];
                        }),
                    ];
                }),
            ];
        });

        return response()->json($comprasJson);
    }

    public function procesarCompraCarrito(Request $request)
    {
        $request->validate([
            'carrito' => 'required|array|min:1',
            'carrito.*.libroId' => 'required|exists:libros,id',
            'carrito.*.formato' => 'required|in:fisico,ar,vr',
            'carrito.*.precio' => 'required|numeric|min:0',
            'carrito.*.cantidad' => 'required|integer|min:1',
            'metodo_pago' => 'required|in:tarjeta,paypal,efectivo',
        ]);

        $usuario = Auth::user();
        $carrito = $request->carrito;
        $metodoPago = $request->metodo_pago;

        // Calcular total
        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        DB::transaction(function () use ($usuario, $carrito, $total, $metodoPago) {
            // Crear pedido
            $pedido = Pedido::create([
                'usuario_id' => $usuario->id,
                'estado' => 'pendiente',
                'total' => $total,
            ]);

            // Crear detalles y reducir stock
            foreach ($carrito as $item) {
                $formato = LibroFormato::where('libro_id', $item['libroId'])
                    ->where('formato', $item['formato'])
                    ->first();

                if (!$formato || $formato->stock < $item['cantidad']) {
                    throw ValidationException::withMessages([
                        'stock' => "Stock insuficiente para {$item['titulo']} en formato {$item['formato']}."
                    ]);
                }

                // Crear detalle
                PedidoDetalle::create([
                    'pedido_id' => $pedido->id,
                    'libro_id' => $item['libroId'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'estado' => 'pendiente',
                    'formato' => $item['formato'],
                ]);

                // Reducir stock
                $formato->decrement('stock', $item['cantidad']);
            }

            // Aquí podrías agregar lógica adicional según el método de pago
            // Por ejemplo, para "efectivo", marcar como pendiente de pago en tienda
            if ($metodoPago === 'efectivo') {
                // Lógica específica para pago en efectivo
            } elseif ($metodoPago === 'tarjeta') {
                // Lógica para procesamiento de tarjeta
                $pedido->update(['estado' => 'pagado']);
            } elseif ($metodoPago === 'paypal') {
                // Lógica para PayPal
                $pedido->update(['estado' => 'pagado']);
            }
        });

        return response()->json([
            'success' => true, 
            'message' => 'Compra procesada exitosamente. ' . 
                        ($metodoPago === 'efectivo' ? 'Recuerda pasar por la tienda para recoger tu pedido y realizar el pago.' : 'Tu pedido ha sido confirmado.')
        ]);
    }
}
