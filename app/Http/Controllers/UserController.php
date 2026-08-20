<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Get user
    public function getUser(Request $req) {
        return $req->user();
    }
    
    // SingUp user
    public function register(Request $req)
    {
        // Validate incoming data
        $ValidatedData = $req->validate([
            'name'     => ['required', 'string'],
            'email'    => ['required', 'string', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'confirmed'],
        ]);

        // Create user in DB
        $user = User::create([
            'name'     => $ValidatedData['name'],
            'email'    => $ValidatedData['email'],
            'password' => Hash::make($ValidatedData['password']),
        ]);

        // Send response
        return response([
            "message" => 'User created successfully...',
            "user"    => $user,
        ], 201);
    }

    public function login(Request $req)
    {
        // Validate incoming data
        $validatedData = $req->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($validatedData)) {
            return response()->json([
                "message" => 'Invalid email or password',
            ], 401);
        }

        // Get the user
        $user = User::where('email', $validatedData['email'])->first();

        // Token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Response
        return response()->json([
            "message" => 'You now logged In ....',
            'user'    => $user,
            'token'   => $token,
        ], 200);
    }

    public function logout(Request $req)
    {
        // Delete current user's token
        $req->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'You are logged out ......',
        ], 200);
    }
}