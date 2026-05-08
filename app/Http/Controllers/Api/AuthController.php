<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Get API token for authentication
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getToken(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (! Auth::attempt($data)) {
                Log::info('[Auth - API] Email atau password salah');

                return response()->json([
                    'message' => 'Email atau password salah',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('api_token')->plainTextToken;
            
            Log::info('User login successfully', ['user_id' => $user->id]);

            return response()->json([
                'message' => 'Login berhasil',
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error saat login', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Error saat login',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout and revoke token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'message' => 'Logout berhasil',
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error saat logout', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Error saat logout',
            ], 500);
        }
    }
}
