<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RequestIdMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Generate unique request ID if not exists
        if (!$request->hasHeader('X-Request-ID')) {
            $requestId = Str::uuid()->toString();
            $request->headers->set('X-Request-ID', $requestId);
        } else {
            $requestId = $request->header('X-Request-ID');
        }

        // Share request ID with logging system
        app()->instance('request-id', $requestId);

        // Add request ID to response headers
        $response = $next($request);
        $response->headers->set('X-Request-ID', $requestId);

        return $response;
    }
}
