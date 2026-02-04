<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RefreshToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Login user
     * Request: email, password
     * Response: access_token + refresh_token
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Buat refresh token
        $refreshToken = RefreshToken::create([
            'user_id' => auth('api')->id(),
            'token' => Str::random(64),
            'expires_at' => Carbon::now()->addDays(14) 
        ]);

        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refreshToken->token,
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    /**
     * Refresh access token menggunakan refresh token
     * Request: refresh_token
     */
    public function refreshToken(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string'
        ]);

        $refreshToken = RefreshToken::where('token', $request->refresh_token)->first();

        if (!$refreshToken || $refreshToken->isExpired()) {
            return response()->json(['error' => 'Invalid or expired refresh token'], 401);
        }

        // generate access token baru
        $token = auth('api')->login($refreshToken->user);

        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refreshToken->token, // tetap bisa pakai yang sama
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    /**
     * Logout user
     * Hapus access token dan refresh token
     */
    public function logout(Request $request)
    {
        $user = auth('api')->user();

        if ($user) {
            // invalidate JWT access token
            auth('api')->logout();

            // hapus semua refresh token user
            RefreshToken::where('user_id', $user->id)->delete();

            return response()->json(['message' => 'Successfully logged out']);
        }

        return response()->json(['error' => 'User not found'], 404);
    }
}