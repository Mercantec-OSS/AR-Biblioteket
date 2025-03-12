<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->department = $request->input('department');
        $user->loggedIn = false;
        if($user->save())
        {
            return redirect('/')->with('message', 'User created successfully');
        }
        else
        {
            return 'User Not Created';
        }
    }

    public function loginUser(Request $request)
    {
        $email = $request->input('emailLogin');
        $password = $request->input('passwordLogin');

        $user = User::where('email', $email)->first();
        if ($user && Hash::check($password, $user->password)) 
        {
            $user->loggedIn = true;
            $user->save();
            
            return redirect('/')->with('message', 'Login successful');
        }

        return 'Invalid credentials';
    }

    // Get user by ID
    public function getUserByID($id)
    {
        $user = User::find($id);
        if ($user) {
            return 'User found: ' . $user->name;
        } else {
            return 'User not found';
        }
    }

    // Get all users
    public function getAllUsers()
    {
        $users = User::all();
        return 'Users: ' . $users->pluck('name')->implode(', ');
    }

    // Edit user by ID
    public function editUserByID(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $user->name = $request->input('name', $user->name);
            $user->email = $request->input('email', $user->email);
            $user->department = $request->input('department', $user->department);
            $user->loggedIn = $request->input('loggedIn', $user->loggedIn);

            // Hash password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }

            if ($user->save()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'User updated successfully'
                    ]);
                }
                return redirect()->back()->with('message', 'User updated successfully');
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'User not found or could not be updated'
            ]);
        }
        return redirect()->back()->withErrors(['message' => 'User not found']);
    }

    // Delete user by ID
    public function deleteUserByID($id)
    {
        $user = User::find($id);
        if ($user) {
            if ($user->delete()) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'User deleted successfully'
                    ]);
                }
                return redirect()->back()->with('message', 'User deleted successfully');
            }
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'User not found or could not be deleted'
            ]);
        }
        return redirect()->back()->withErrors(['message' => 'User not found']);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
