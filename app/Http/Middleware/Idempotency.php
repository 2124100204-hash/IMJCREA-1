<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class Idempotency
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Solo aplicar idempotencia a métodos POST, PUT, PATCH
        if (!in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            return $next($request);
        }

        // Obtener la clave de idempotencia del header
        $idempotencyKey = $request->header('Idempotency-Key');

        if (!$idempotencyKey) {
            // Si no hay clave, generar una basada en el contenido de la solicitud
            $idempotencyKey = $this->generateKeyFromRequest($request);
        }

        $cacheKey = 'idempotency_' . $idempotencyKey;

        // Verificar si ya fue procesada
        if (Cache::has($cacheKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Esta solicitud ya fue procesada anteriormente'
            ], 409);
        }

        // Procesar la solicitud
        $response = $next($request);

        // Si la respuesta fue exitosa (código 2xx), marcar como procesada
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            Cache::put($cacheKey, true, now()->addMinutes(30)); // Expira en 30 minutos
        }

        return $response;
    }

    /**
     * Generar una clave de idempotencia basada en el contenido de la solicitud
     */
    private function generateKeyFromRequest(Request $request): string
    {
        $userId = auth()->id() ?? 'anonymous';
        $method = $request->method();
        $path = $request->path();
        $body = $request->getContent();

        return md5($userId . $method . $path . $body);
    }
}
