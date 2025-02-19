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
            'token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }

        // Read and verify the token
        $adminTokenPath = config_path('admin_token.json');
        if (!file_exists($adminTokenPath)) {
            return back()->withErrors(['token' => 'System configuration error'])->withInput();
        }

        $adminToken = json_decode(file_get_contents($adminTokenPath), true);
        if ($request->get('token') !== $adminToken['registration_token']) {
            return back()->withErrors(['token' => 'Din token er forkert'])->withInput();
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
            // Get the token from cookie
            $token = request()->cookie('jwt_token');
            
            if ($token) {
                // Set and invalidate the token
                JWTAuth::setToken($token);
                JWTAuth::invalidate(true); // Force token invalidation
            }
            
            // Logout the user from session
            auth()->logout();
            
            // Create cookie instance with immediate expiration
            $cookie = cookie()->forget('jwt_token');
            
            // Set cookie parameters for proper removal
            $cookie->withExpired()
                   ->withSecure(true)
                   ->withHttpOnly(true)
                   ->withSameSite('strict')
                   ->withDomain(null)
                   ->withPath('/');
            
            // Clear session
            session()->flush();
            
            // Redirect with cookie removal
            return redirect('/')
                ->withCookie($cookie)
                ->with('success', 'Du er nu logget ud');
            
        } catch (\Exception $e) {
            \Log::error('Logout error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Der opstod en fejl under log ud');
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
            $user = JWTAuth::authenticate();
            
            return $user ? true : false;
        } catch (\Exception $e) {
            return false;
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