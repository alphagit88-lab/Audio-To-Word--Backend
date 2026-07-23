<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminWebMiddleware
{
    /**
     * Handle an incoming web request for the Admin Portal.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Please log in to access the Admin Web Portal.');
        }

        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login')->with('error', 'Access denied. Only administrators can access the Admin Portal.');
        }

        return $next($request);
    }
}
