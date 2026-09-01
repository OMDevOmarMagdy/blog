<?php
namespace App\Http\Controllers;

use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\ResetPasswordRequest;
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

    // Reset password
    // {
    // "email": "user@gmail.com",
    // "token": "THE_TOKEN_FROM_EMAIL",
    // "password": "newPassword123",
    // "password_confirmation": "newPassword123"
    // }

    public function resetPassword(ResetPasswordRequest $req)
    {
        // Validate the request data
        $validatedData = $req->validated();

        // Find the user by email
        $user = User::where('email', $validatedData['email'])->first();

        if (! $user) {
            return response()->json([
                'message' => "If the email exists, a password reset link has been sent.",
            ], 404);
        }

        // Check if the token is valid
        $resetToken = DB::table('password_reset_tokens')->where('email', $user->email)->first();
        if (! $resetToken) {
            return response()->json([
                'message' => 'Invalid or expired reset token',
            ], 400);
        }

        // Check if the token has expired ( 60 minutes)
        if ($resetToken->created_at < now()->subMinutes(60)) {

            DB::table('password_reset_tokens')->where('email', $user->email)->delete();

            return response()->json([
                'message' => 'Reset token has expired',
            ], 400);
        }

        // Check if the provided token matches the hashed token in the database
        if (! Hash::check($validatedData['token'], $resetToken->token)) {
            return response()->json([
                'message' => 'Invalid or expired reset token',
            ], 400);
        }

        // Update the user's password
        $user->password = Hash::make($validatedData['password']);
        $user->save();

        // Delete the token after successful password reset
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        return response()->json([
            'message' => 'If the email exists, a password reset link has been sent.',
        ], 200);
    }
}
