<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request (API).
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $user = $request->user();

        // hapus token lama (optional)
        $user->tokens()->delete();

        // buat token baru
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * Logout API (revoke token).
     */
    public function destroy(Request $request)
    {
        $token = $request->user()->currentAccessToken();
        if ($token) {
            $request->user()->tokens()->where('id', $token->id)->delete();
        }

        return response()->json([
            'message' => 'Logged out'
        ]);
    }
}
