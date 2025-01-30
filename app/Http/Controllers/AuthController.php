<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $token = auth()->attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 401,
                'message' => 'Ya fucking wrong mate'
            ], 401);
        }

        return response()->json([
            'token' => $token,
            'user' => auth()->user(),
            'expire_in' => auth()->factory()->getTTL()*60
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
