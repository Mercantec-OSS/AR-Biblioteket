<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JWTMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Attempt to get the token from the request header or cookie
            $token = $request->cookie('jwt_token') ?: $request->bearerToken();

            if (!$token) {
                Log::warning('Token not provided');
                return response()->json(['error' => 'Token not provided'], 401);
            }

            // Try to parse and authenticate the token
            JWTAuth::setToken($token);

            if (!JWTAuth::check()) {
                Log::warning('Invalid token');
                return response()->json(['error' => 'Invalid token'], 401);
            }

            // Proceed if the token is valid
            return $next($request);

        } catch (JWTException $e) {
            Log::warning('JWT Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Token is invalid or expired'], 401);
        }
    }
}
