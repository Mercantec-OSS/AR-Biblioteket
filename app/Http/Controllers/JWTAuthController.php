<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;


class JWTAuthController extends Controller
{
    // User registration
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6', // Removed the confirmed rule
            'department' => 'required|string|max:255', // Department validation remains
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'department' => $request->get('department'),
            'loggedIn' => '0'
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }

    // User login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            
            // Generate the token
            $token = JWTAuth::fromUser($user);

            // Log the generated token and the JWT secret
            Log::debug('Generated Token: ' . $token);  // Log the token
            Log::debug('JWT_SECRET: ' . env('JWT_SECRET'));  // Log the secret being used

            // Set token in a cookie and return the response
            return response()->json(['message' => 'Login successful'])->cookie(
                'jwt_token', $token, 60, null, null, true, true // Secure cookie
            );
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // User logout
    public function logout()
    {
        try {
            auth()->logout();
            return response()->json(['message' => 'Logged out successfully'])
                             ->cookie('jwt_token', '', -1); // Expire the cookie
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not log out'], 500);
        }
    }

    public function isAuthenticated(Request $request)
    {
        try {
            $token = $request->cookie('jwt_token');

            if (!$token) {
                return false; // No token present
            }

            JWTAuth::setToken($token);
            return JWTAuth::check();
        } catch (JWTException $e) {
            return false; // Invalid or expired token
        }
    }

    public function getUser(Request $request)
    {
        try {
            // Get the token from the request's cookie
            $token = $request->cookie('jwt_token');
            
            if (!$token) {
                return response()->json(['error' => 'No token provided'], 401);
            }

            // Set the token
            JWTAuth::setToken($token);

            // Authenticate the user
            $user = JWTAuth::authenticate();

            return response()->json(compact('user'));
        } catch (JWTException $e) {
            return response()->json(['error' => 'Invalid token'], 400);
        }
    }

    public function refreshToken(Request $request)
    {
        try {
            $token = $request->cookie('jwt_token');
            
            if (!$token) {
                return response()->json(['error' => 'No token provided'], 401);
            }

            JWTAuth::setToken($token);
            $newToken = JWTAuth::refresh();

            // Optionally, you can also update the cookie with the new token
            return response()->json(['token' => $newToken])->cookie('jwt_token', $newToken, 60, null, null, true, true);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token refresh failed'], 500);
        }
    }

}