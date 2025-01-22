<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
            $user->password = $request->input('password', $user->password);
            $user->loggedIn = $request->input('loggedIn', $user->loggedIn);
            $user->save();
            return 'User updated successfully';
        } else {
            return 'User not found';
        }
    }

    // Delete user by ID
    public function deleteUserByID($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return 'User deleted successfully';
        } else {
            return 'User not found';
        }
    }
}
