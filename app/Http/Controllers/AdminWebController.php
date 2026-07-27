<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Usage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminWebController extends Controller
{
    /**
     * Show Admin Web Login Form
     */
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Handle Admin Login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role !== 'admin') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withInput($request->only('email'))
                    ->with('error', 'Access denied. Only administrators are permitted to access the web admin panel.');
            }

            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()->withInput($request->only('email'))
            ->with('error', 'Invalid email address or password.');
    }

    /**
     * Admin Dashboard Overview Page
     */
    public function dashboard()
    {
        $totalUsers    = User::count();
        $activeUsers   = User::where('is_active', true)->count();
        $inactiveUsers = User::where('is_active', false)->count();
        $adminCount    = User::where('role', 'admin')->count();
        $recentUsers   = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'adminCount',
            'recentUsers'
        ));
    }

    /**
     * User Management Listing Page
     */
    public function usersIndex(Request $request)
    {
        $search = $request->query('search');

        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * Create New User Account
     */
    public function userStore(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6',
            'is_active' => 'nullable|boolean',
        ]);

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role'      => 'user',
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.users')->with('success', 'User account created successfully.');
    }

    /**
     * Update Existing User Account
     */
    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $id,
            'password'  => 'nullable|string|min:6',
            'is_active' => 'nullable|boolean',
        ]);

        $updateData = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'is_active' => $request->has('is_active') ? true : false,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.users')->with('success', 'User account updated successfully.');
    }

    /**
     * Delete User Account
     */
    public function userDestroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')->with('error', 'You cannot delete your own logged-in admin account.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User account deleted successfully.');
    }

    /**
     * Usage Logs Admin Page
     */
    public function usageIndex(Request $request)
    {
        $users      = User::orderBy('name')->get();
        $filterUser = $request->query('user_id');

        $query = Usage::with('user')->orderBy('created_at', 'desc');

        if ($filterUser) {
            $query->where('user_id', $filterUser);
        }

        $usages = $query->paginate(25)->withQueryString();

        return view('admin.usage.index', compact('usages', 'users', 'filterUser'));
    }

    /**
     * Settings Page
     */
    public function settingsIndex()
    {
        $geminiApiKeys = Setting::getValue('gemini_api_keys', '');
        return view('admin.settings.index', compact('geminiApiKeys'));
    }

    /**
     * Update Settings
     */
    public function settingsUpdate(Request $request)
    {
        $request->validate([
            'gemini_api_keys' => 'nullable|string',
        ]);

        Setting::setValue('gemini_api_keys', $request->input('gemini_api_keys', ''));

        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully.');
    }

    /**
     * Admin Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }
}
