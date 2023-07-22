<?php

namespace App\Http\Controllers\Auth;

use App\Events\SendEmailEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // login user
        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => ['Invalid login details'],
                'status' => 401
            ]);
        }

        // generate token
        $token = auth()->user()->createToken('authToken')->plainTextToken;
        return response()->json([
            'message' => 'Login successful',
            'user' => auth()->user(),
            'status' => 200,
            'token' => $token
        ]);
    }


    public function register(Request $request)
    {
        // api validation
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed'
        ]);

        // create data
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password) // encrypt password
        ];

        // store data
        $data = User::create($data);

        // login this user 
        Auth::login($data);
        $user = Auth::user();

        $token = $user->createToken('authToken')->plainTextToken;

        // FOR SEND A WELCOME MAIL
        event(new SendEmailEvent($user));

        return response()->json([
            'message' => 'Registration successful',
            'user' => auth()->user(),
            'status' => 200,
            'token' => $token
        ]);
    }


    function getUsers()
    {
        return User::where('id', '!=', auth()->id())->get();
    }


    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout successful',
            'status' => 200
        ]);
    }
}
