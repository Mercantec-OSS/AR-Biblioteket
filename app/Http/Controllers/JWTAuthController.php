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
            'password' => 'required|string|min:6',
            'department' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'department' => $request->get('department'),
            'loggedIn' => '0'
        ]);

        return redirect('/login')->with('success', 'Konto oprettet succesfuldt. Log venligst ind.');
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
            Log::debug('Generated Token: ' . $token);
            Log::debug('JWT_SECRET: ' . env('JWT_SECRET'));

            // Redirect to home page with cookie
            return redirect('/')->withCookie(
                'jwt_token', 
                $token, 
                60, 
                null, 
                null, 
                true, 
                true
            );
        }

        return back()->withErrors(['error' => 'Invalid credentials']);
    }

    // User logout
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            auth()->logout();
            
            return redirect('/')->withCookie(Cookie::forget('jwt_token'));
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Could not log out');
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