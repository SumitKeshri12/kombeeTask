<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class EnsureIdempotency
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Only apply to POST, PUT, PATCH, DELETE methods
        if (!in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return $next($request);
        }

        $idempotencyKey = $request->header('Idempotency-Key');

        // Require Idempotency-Key header for mutating requests
        if (!$idempotencyKey) {
            return Response::json([
                'error' => 'Idempotency-Key header is required for this request'
            ], SymfonyResponse::HTTP_BAD_REQUEST);
        }

        // Create a unique cache key for this request
        $cacheKey = $this->generateCacheKey($request, $idempotencyKey);

        // Check if we have a cached response
        if (Cache::has($cacheKey)) {
            $cachedResponse = Cache::get($cacheKey);
            return Response::json($cachedResponse['data'], $cachedResponse['status'], [
                'X-Idempotent-Replayed' => 'true'
            ]);
        }

        // Process the request
        $response = $next($request);

        // Cache the response for future identical requests
        // Store for 24 hours by default
        Cache::put($cacheKey, [
            'data' => json_decode($response->getContent(), true),
            'status' => $response->getStatusCode()
        ], now()->addSecond(config('idempotency.cache_duration', 20)));

        return $response;
    }

    /**
     * Generate a unique cache key for the request
     *
     * @param Request $request
     * @param string $idempotencyKey
     * @return string
     */
    private function generateCacheKey(Request $request, string $idempotencyKey): string
    {
        return md5(implode('|', [
            $request->method(),
            $request->path(),
            $idempotencyKey,
            $request->user() ? $request->user()->id : 'guest'
        ]));
    }
} 