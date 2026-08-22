<?php
namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Get user
    public function getUser(Request $req)
    {
        return $req->user();
    }

    // SingUp user
    public function register(RegisterUserRequest $req)
    {
        $data = $req->validated();

        // Create user in DB
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Send response
        return response([
            "message" => 'User created successfully...',
            "user"    => $user,
        ], 201);
    }

    public function login(LoginUserRequest $req)
    {
        $validatedData = $req->validated();

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
