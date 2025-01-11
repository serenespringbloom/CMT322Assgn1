<?php
// app/Http/Controllers/AdminAuthController.php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        header('Access-Control-Allow-Origin: http://localhost:5174');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        
        if ($request->isMethod('OPTIONS')) {
            return response()->json([], 200);
        }

        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $admin = Admin::where('username', $credentials['username'])->first();

        if ($admin && Hash::check($credentials['password'], $admin->password_hash)) {
            // Generate new token
            $token = Str::random(60);
            
            // Store token
            $admin->remember_token = $token;
            $admin->save();

            return response()->json([
                'token' => $token,
                'admin' => [
                    'admin_id' => $admin->admin_id,
                    'username' => $admin->username,
                    'email' => $admin->email
                ]
            ]);
        }

        return response()->json([
            'error' => 'Invalid credentials'
        ], 401);
    }

    public function dashboard(Request $request)
    {
        header('Access-Control-Allow-Origin: http://localhost:5174');
        header('Access-Control-Allow-Headers: Authorization');
        
        return response()->json([
            'message' => 'Welcome to dashboard',
            'admin' => $request->admin
        ]);
    }
}