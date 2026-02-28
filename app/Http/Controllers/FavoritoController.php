<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritoController extends Controller
{
    public function agregar(Request $request)
    {
        /** @var Usuario $usuario */
        $usuario = Auth::user();
        $libroId = $request->input('libro_id');
        
        // Obtener favoritos actuales o crear array vacío
        $favoritos = $usuario->favoritos ?? [];
        
        // Convertir a array si es json
        if (is_string($favoritos)) {
            $favoritos = json_decode($favoritos, true);
        }
        
        // Verificar si ya está en favoritos
        if (!in_array($libroId, $favoritos)) {
            $favoritos[] = $libroId;
            $usuario->update(['favoritos' => $favoritos]);
            
            return response()->json([
                'success' => true,
                'message' => 'Agregado a favoritos',
                'favoritos' => $favoritos
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Ya está en favoritos'
        ]);
    }
    
    public function eliminar(Request $request)
    {
        /** @var Usuario $usuario */
        $usuario = Auth::user();
        $libroId = $request->input('libro_id');
        
        // Obtener favoritos actuales
        $favoritos = $usuario->favoritos ?? [];
        
        // Convertir a array si es json
        if (is_string($favoritos)) {
            $favoritos = json_decode($favoritos, true);
        }
        
        // Eliminar del array
        $favoritos = array_diff($favoritos, [$libroId]);
        $favoritos = array_values($favoritos); // Reindexar el array
        
        $usuario->update(['favoritos' => $favoritos]);
        
        return response()->json([
            'success' => true,
            'message' => 'Eliminado de favoritos',
            'favoritos' => $favoritos
        ]);
    }
    
    public function obtener()
    {
        /** @var Usuario $usuario */
        $usuario = Auth::user();
        $favoritos = $usuario->favoritos ?? [];
        
        return response()->json([
            'favoritos' => $favoritos
        ]);
    }
}
