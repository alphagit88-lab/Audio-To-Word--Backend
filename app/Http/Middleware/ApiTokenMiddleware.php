<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class ApiTokenMiddleware
{
    /**
     * Validate the Bearer API token on every protected request.
     * Sets the authenticated user on the request for downstream use.
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthenticated. No token provided.'], 401);
        }

        $user = User::where('api_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated. Invalid or expired token.'], 401);
        }

        // Bind the authenticated user onto the current request cycle
        auth()->setUser($user);

        return $next($request);
    }
}
