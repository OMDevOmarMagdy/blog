<?php
namespace App\Http\Controllers;

use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Mail\ForgetPassMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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

    public function forgetPassword(ForgetPasswordRequest $req)
    {
        $validatedData = $req->validated();
        $user          = User::where('email', $validatedData['email'])->first();

        if (! $user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        // Generate a password reset token
        $token = bin2hex(random_bytes(32));

        // Store hashed token in database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token'      => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Send the password reset email
        Mail::to($user->email)->send(new ForgetPassMail($user->name, $user->email, $token));

        return response()->json([
            'message' => 'Password reset email sent successfully',
        ], 200);
    }
}
