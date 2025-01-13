<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    /**
     * Show the login form for admins.
     */
    public function showLoginForm()
    {
        return view('admin.login'); // Return the login view
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    
        $admin = DB::table('admins')->where('username', $request->username)->first();
    
        if ($admin && Hash::check($request->password, $admin->password_hash)) {
            // Generate a token (you can use a library like JWT or a random string)
            $token = Str::random(60);
    
            // Save the token in the database for the admin
            DB::table('admins')->where('admin_id', $admin->admin_id)->update(['remember_token' => $token]);
    
            return response()->json([
                'success' => true,
                'token' => $token, // Return the token
                'data' => $admin,
            ]);
        }
    
        return response()->json(['success' => false, 'error' => 'Invalid credentials.'], 401);
    }
    
    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        // Get the token from the request
        $token = $request->bearerToken();
    
        // Find the admin using the token and clear it
        if ($token) {
            DB::table('admins')->where('remember_token', $token)->update(['remember_token' => null]);
        }
    
        // Return a response
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }
    
    

    /**
     * Admin dashboard.
     */
    public function dashboard()
    {
        return response()->json([
            'message' => 'Welcome to the Admin Dashboard',
        ]);
    }
}
