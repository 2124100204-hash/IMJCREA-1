<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CircuitBreaker
{
    private const FAILURE_THRESHOLD = 5;
    private const TIMEOUT_SECONDS = 30;
    private const RECOVERY_TIME_SECONDS = 60;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $serviceKey = 'circuit_breaker_' . $request->getHost();

        // Verificar si el circuito está abierto
        if ($this->isCircuitOpen($serviceKey)) {
            Log::warning('Circuit breaker is open, returning cached response or fallback');
            return $this->getFallbackResponse($request);
        }

        try {
            // Ejecutar con timeout
            $response = $this->executeWithTimeout($next, $request);

            // Resetear contador de fallos en éxito
            Cache::forget($serviceKey . '_failures');
            Cache::forget($serviceKey . '_last_failure');

            return $response;

        } catch (\Exception $e) {
            $this->recordFailure($serviceKey, $e);
            Log::error('Circuit breaker caught exception: ' . $e->getMessage());

            // Si el circuito se abre, devolver fallback
            if ($this->isCircuitOpen($serviceKey)) {
                return $this->getFallbackResponse($request);
            }

            // Re-lanzar la excepción si el circuito aún está cerrado
            throw $e;
        }
    }

    private function executeWithTimeout(Closure $next, Request $request): Response
    {
        // Usar pcntl_alarm si está disponible (solo en sistemas Unix-like)
        if (function_exists('pcntl_alarm')) {
            pcntl_alarm(self::TIMEOUT_SECONDS);

            try {
                $response = $next($request);
                pcntl_alarm(0); // Cancelar alarma
                return $response;
            } catch (\Exception $e) {
                pcntl_alarm(0);
                throw $e;
            }
        } else {
            // Fallback: ejecutar normalmente sin timeout preciso
            return $next($request);
        }
    }

    private function isCircuitOpen(string $serviceKey): bool
    {
        $failures = Cache::get($serviceKey . '_failures', 0);
        $lastFailure = Cache::get($serviceKey . '_last_failure');

        if ($failures >= self::FAILURE_THRESHOLD) {
            // Verificar si ha pasado el tiempo de recuperación
            if ($lastFailure && now()->diffInSeconds($lastFailure) < self::RECOVERY_TIME_SECONDS) {
                return true;
            } else {
                // Resetear contador después del tiempo de recuperación
                Cache::forget($serviceKey . '_failures');
                Cache::forget($serviceKey . '_last_failure');
                return false;
            }
        }

        return false;
    }

    private function recordFailure(string $serviceKey, \Exception $e): void
    {
        $failures = Cache::get($serviceKey . '_failures', 0) + 1;
        Cache::put($serviceKey . '_failures', $failures, now()->addMinutes(10));
        Cache::put($serviceKey . '_last_failure', now(), now()->addMinutes(10));
    }

    private function getFallbackResponse(Request $request): Response
    {
        // Devolver una respuesta de fallback
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Servicio temporalmente no disponible. Intente nuevamente en unos minutos.',
                'fallback' => true
            ], 503);
        }

        // Para vistas, devolver una página de mantenimiento
        return response()->view('errors.maintenance', [], 503);
    }
}
