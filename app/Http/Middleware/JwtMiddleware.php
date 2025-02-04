<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            // Check for a token in the request header or cookie
            $token = $request->bearerToken(); // Retrieves the "Authorization: Bearer" token
            if (!$token) {
                $token = $request->cookie('jwt_token'); // Changed from 'token' to 'jwt_token'
            }

            if (!$token) {
                return response()->json(['error' => 'Token not provided'], 401);
            }

            // Authenticate the token
            JWTAuth::setToken($token); // Set the token for JWTAuth
            $user = JWTAuth::authenticate(); // Authenticate the user
            $request->attributes->set('user', $user); // Pass the user to the request if needed
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        } catch (Exception $e) {
            return response()->json(['error' => 'Token is missing or malformed'], 401);
        }

        // Proceed to the next middleware
        return $next($request);
    }
}
