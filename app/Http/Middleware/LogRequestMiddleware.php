<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogRequestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::check() ? Auth::user()->email : 'guest';
        $method = $request->method();
        $uri = $request->getRequestUri();

        Log::info("Request: {$method} {$uri} by {$user}");

        $response = $next($request);

        if ($response->isRedirection()) {
            Log::info("Redirected to " . $response->headers->get('Location'));
        }

        return $response;
    }
}
