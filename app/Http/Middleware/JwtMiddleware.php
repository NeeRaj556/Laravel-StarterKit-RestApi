<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next, $role = null)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            if ($role && $user->role !== $role) {
                return response()->json(['error' => 'Access denied. This route is for ' . $role . ' only.'], 403);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token Invalid or Expired'], 401);
        }

        return $next($request);
    }
}
