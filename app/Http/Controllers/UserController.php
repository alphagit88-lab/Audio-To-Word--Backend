<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class UserController extends Controller
{
    /**
     * List all non-admin users.
     * GET /api/admin/users
     */
    public function index()
    {
        $users = User::where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($u) => [
                'id'         => $u->id,
                'first_name' => $u->first_name ?? '',
                'last_name'  => $u->last_name ?? '',
                'email'      => $u->email,
                'is_active'  => $u->is_active,
                'created_at' => $u->created_at->toDateTimeString(),
            ]);

        return response()->json(['users' => $users]);
    }

    /**
     * Create a new user account (admin action).
     * POST /api/admin/users
     * Body: { name, email, password }
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'password'   => ['required', 'string', Password::min(6)],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => 'user',
            'is_active'  => true,
            'status'     => 'approved',
        ]);

        return response()->json([
            'message' => 'User created successfully.',
            'user'    => [
                'id'         => $user->id,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'email'      => $user->email,
                'is_active'  => $user->is_active,
            ],
        ], 201);
    }

    /**
     * Update an existing user's details.
     * PUT /api/admin/users/{id}
     * Body: { name?, email?, password?, is_active? }
     */
    public function update(Request $request, int $id)
    {
        $user = User::where('id', $id)->where('role', 'user')->firstOrFail();

        $request->validate([
            'first_name' => 'sometimes|string|max:100',
            'last_name'  => 'sometimes|string|max:100',
            'email'      => 'sometimes|email|unique:users,email,' . $id,
            'password'   => ['sometimes', 'string', Password::min(6)],
            'is_active'  => 'sometimes|boolean',
        ]);

        $data = $request->only(['first_name', 'last_name', 'email', 'is_active']);
        
        if (isset($data['first_name']) || isset($data['last_name'])) {
            $firstName = $data['first_name'] ?? $user->first_name;
            $lastName = $data['last_name'] ?? $user->last_name;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
            // Invalidate existing token when password changes
            $data['api_token'] = null;
        }

        if (isset($data['is_active']) && !$data['is_active']) {
            // Revoke token when account is deactivated
            $data['api_token'] = null;
        }

        $user->update($data);

        return response()->json([
            'message' => 'User updated successfully.',
            'user'    => [
                'id'         => $user->id,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'email'      => $user->email,
                'is_active'  => $user->is_active,
            ],
        ]);
    }

    /**
     * Permanently delete a user.
     * DELETE /api/admin/users/{id}
     */
    public function destroy(int $id)
    {
        $user = User::where('id', $id)->where('role', 'user')->firstOrFail();
        $user->delete();

        return response()->json(['message' => 'User deleted successfully.']);
    }

    /**
     * Get a single user's details.
     * GET /api/admin/users/{id}
     */
    public function show(int $id)
    {
        $user = User::where('id', $id)->where('role', 'user')->firstOrFail();

        return response()->json([
            'user' => [
                'id'         => $user->id,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'email'      => $user->email,
                'is_active'  => $user->is_active,
                'created_at' => $user->created_at->toDateTimeString(),
            ],
        ]);
    }
}
