<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cupon;
use App\Models\Canje;
use Illuminate\Support\Facades\Auth;

class CuponController extends Controller
{
    public function index()
    {
        return view('cupones.index');
    }

public function canjear(Request $request)
{
    // Usamos el validador manual para que no haga redirect automático
    $validator = \Validator::make($request->all(), [
        'codigo' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false, 
            'message' => 'El código de cupón es obligatorio.'
        ], 422);
    }

    $codigo = strtoupper($request->codigo);
    $cupon = \App\Models\Cupon::where('codigo', $codigo)->first();

    if (!$cupon) {
        return response()->json([
            'success' => false, 
            'message' => 'Código de cupón inválido.'
        ], 404);
    }

    $usuario = \Illuminate\Support\Facades\Auth::user();

    $yaCanjeado = \App\Models\Canje::where('usuario_id', $usuario->id)
                       ->where('cupon_id', $cupon->id)
                       ->exists();

    if ($yaCanjeado) {
        return response()->json([
            'success' => false, 
            'message' => 'Ya has canjeado este cupón anteriormente.'
        ], 422);
    }

    \App\Models\Canje::create([
        'usuario_id' => $usuario->id,
        'cupon_id' => $cupon->id,
    ]);

    return response()->json([
        'success' => true,
        'message' => '¡Cupón aplicado!',
        'premio'  => $cupon->premio
    ]);
}
}