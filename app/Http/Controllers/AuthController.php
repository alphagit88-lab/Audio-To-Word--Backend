<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Authenticate a user with email and password.
     * Returns a persistent API token on success.
     *
     * POST /api/login
     * Body: { email, password }
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid email or password.'], 401);
        }

        // Removed 403 checks for pending/rejected/inactive accounts. 
        // Frontend will handle blocking access to features based on status.

        // Generate a token if not already present
        if (!$user->api_token) {
            $user->api_token = Str::random(80);
            $user->save();
        }

        return response()->json([
            'message' => 'Login successful.',
            'token'   => $user->api_token,
            'user'    => [
                'id'         => $user->id,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'email'      => $user->email,
                'role'       => $user->role,
                'status'     => $user->status,
                'is_active'  => $user->is_active,
            ],
        ]);
    }

    /**
     * Validate the current bearer token and return the authenticated user.
     * Used by the Electron app on startup to check for a stored session.
     *
     * GET /api/validate-token
     * Header: Authorization: Bearer <token>
     */
    public function validateToken(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'valid' => true,
            'user'  => [
                'id'         => $user->id,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'email'      => $user->email,
                'role'       => $user->role,
                'status'     => $user->status,
                'is_active'  => $user->is_active,
            ],
        ]);
    }

    /**
     * Logout — invalidate the current API token.
     *
     * POST /api/logout
     * Header: Authorization: Bearer <token>
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->api_token = null;
        $user->save();

        return response()->json(['message' => 'Logged out successfully.']);
    }
}
