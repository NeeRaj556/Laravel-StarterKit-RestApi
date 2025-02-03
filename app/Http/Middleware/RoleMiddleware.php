<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user || $user->role !== $role) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
