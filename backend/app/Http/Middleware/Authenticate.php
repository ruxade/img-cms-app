<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        // Check if a user is authenticated via Sanctum.
        // Sanctum automatically sets the authenticated user if a valid token is present.
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        return $next($request);
    }
}
