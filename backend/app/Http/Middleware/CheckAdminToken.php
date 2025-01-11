<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin;

class CheckAdminToken
{
    public function handle(Request $request, Closure $next)
    {
        header('Access-Control-Allow-Origin: http://localhost:5174');
        header('Access-Control-Allow-Headers: Authorization, Content-Type');
        
        if ($request->isMethod('OPTIONS')) {
            return response()->json([], 200);
        }

        $token = $request->header('Authorization');
        
        if (!$token) {
            return response()->json(['error' => 'No token provided'], 401);
        }

        $admin = Admin::where('remember_token', $token)->first();
        
        if (!$admin) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $request->admin = $admin;
        return $next($request);
    }
}