<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyAdminToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken(); // Get the token from the Authorization header
        
        // Verify if the token is valid (e.g., check if it's in the database)
        if (!$token || !auth('admin')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request); // Allow the request to proceed
    }
}
