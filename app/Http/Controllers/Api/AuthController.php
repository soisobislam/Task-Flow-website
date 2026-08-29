<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ============================================================
    // LOGIN
    // ============================================================

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where(
            'email',
            $credentials['email']
        )->first();

        if (
            ! $user ||
            ! Hash::check(
                $credentials['password'],
                $user->password
            )
        ) {
            return response()->json([
                'message' =>
                    'The provided credentials are incorrect.',
            ], 401);
        }

        // Only employees can use the Flutter application.
        if (! $user->isEmployee()) {
            return response()->json([
                'message' =>
                    'Only employees can use the mobile application.',
            ], 403);
        }

        $token = $user
            ->createToken('flutter-app')
            ->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
            'user' => $user,
        ]);
    }


    // ============================================================
    // REGISTER
    // ============================================================

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);


        // ========================================================
        // CREATE EMPLOYEE
        // ========================================================

        $user = User::create([
            'name' => $validated['name'],

            'email' => $validated['email'],

            'password' => Hash::make(
                $validated['password']
            ),

            'role' => 'employee',
        ]);


        // ========================================================
        // CREATE FLUTTER API TOKEN
        // ========================================================

        $token = $user
            ->createToken('flutter-app')
            ->plainTextToken;


        // ========================================================
        // RESPONSE
        // ========================================================

        return response()->json([
            'message' =>
                'Account created successfully.',

            'token' => $token,

            'user' => $user,
        ], 201);
    }


    // ============================================================
    // LOGOUT
    // ============================================================

    public function logout(Request $request): JsonResponse
    {
        $request
            ->user()
            ->currentAccessToken()
            ->delete();

        return response()->json([
            'message' =>
                'Logged out successfully.',
        ]);
    }


    // ============================================================
    // CURRENT USER
    // ============================================================

    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }
}