<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Cookie;

class JWTAuthController extends Controller
{
    // User registration
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'), 201);
    }

    // User login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emailLogin' => 'required|email',
            'passwordLogin' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $credentials = [
            'email' => $request->get('emailLogin'),
            'password' => $request->get('passwordLogin')
        ];

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            // Create a secure HTTP-only cookie
            $cookie = cookie('jwt_token', $token, config('jwt.ttl'), null, null, true, true);

            // Set the guard
            auth('api')->setToken($token);

            // For web forms, redirect after successful login
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'user' => auth('api')->user()
                ])->withCookie($cookie);
            }

            // Redirect for web form submissions
            return redirect('/')->withCookie($cookie);
            
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }

    // Get authenticated user
    public function getUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 404);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        return response()->json(compact('user'));
    }

    // User logout
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            
            // Remove the cookie
            $cookie = cookie()->forget('jwt_token');
            
            return response()->json(['message' => 'Successfully logged out'])
                ->withCookie($cookie);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to logout'], 500);
        }
    }

    public function isAuthenticated(Request $request)
    {
        try {
            $token = $request->cookie('jwt_token');
            if (!$token) {
                return false;
            }
            JWTAuth::setToken($token);
            return JWTAuth::check();
        } catch (JWTException $e) {
            return false;
        }
    }
}