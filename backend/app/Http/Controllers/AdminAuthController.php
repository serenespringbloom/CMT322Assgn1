<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminAuthController extends Controller
{
    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if ($admin && Hash::check($request->password, $admin->password_hash)) {
            // Generate a token and set expiration (e.g., 1 hour from now)
            $plainTextToken = Str::random(60);
            $expiresAt = Carbon::now()->addHour();

            // Save the token in the personal_access_tokens table
            $token = PersonalAccessToken::create([
                'tokenable_type' => Admin::class,
                'tokenable_id' => $admin->admin_id,
                'name' => 'admin-access-token',
                'token' => hash('sha256', $plainTextToken),
                'abilities' => json_encode(['*']),
                'expires_at' => $expiresAt,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'token' => $plainTextToken,
                'expires_at' => $token->expires_at,
                'admin' => [
                    'id' => $admin->admin_id,
                    'username' => $admin->username,
                    'email' => $admin->email,
                ],
            ]);
        }

        return response()->json(['success' => false, 'error' => 'Invalid credentials.'], 401);
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        if ($token) {
            PersonalAccessToken::where('token', hash('sha256', $token))->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    /**
     * Middleware to check token validity and expiration.
     */
public function checkToken(Request $request)
{
    $token = $request->bearerToken();

    if (!$token) {
        return response()->json(['success' => false, 'message' => 'Token not provided.'], 401);
    }

    // Cleanup all expired tokens
    DB::table('personal_access_tokens')
    ->where('expires_at', '<', now())
    ->delete();

    $personalAccessToken = PersonalAccessToken::where('token', hash('sha256', $token))->first();

    // If token doesn't exist or has expired
    if (!$personalAccessToken || $personalAccessToken->expires_at < now()) {
        // Clean up the expired token
        if ($personalAccessToken) {
            $personalAccessToken->delete();
        }

        return response()->json(['success' => false, 'message' => 'Token is invalid or expired.'], 401);
    }

    // Token is valid
    return response()->json(['success' => true, 'message' => 'Token is valid.']);
}
   
}
