<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Handle a public registration request.
     * POST /api/register
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'phone'        => 'required|string|max:30',
            'email'        => 'required|email|unique:users,email',
            'password'     => ['required', 'string', Password::min(6)],
            'payment_proof'=> 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:10240',
        ]);

        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/payment_proofs'), $filename);
            $paymentProofPath = 'payment_proofs/' . $filename;
        }

        $user = User::create([
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'phone'         => $request->phone,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role'          => 'user',
            'is_active'     => false,
            'status'        => 'pending',
            'payment_proof' => $paymentProofPath,
        ]);

        return response()->json([
            'message' => 'Registration successful. Please wait for admin approval before logging in.',
        ], 201);
    }

    /**
     * List pending users for admin approval.
     * GET /api/admin/pending-users
     */
    public function pendingUsers()
    {
        $users = User::where('role', 'user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn(User $u) => $this->formatUser($u));

        return response()->json(['users' => $users]);
    }

    /**
     * Approve a pending user.
     * POST /api/admin/pending-users/{id}/approve
     */
    public function approve(int $id)
    {
        $user = User::where('id', $id)->where('role', 'user')->firstOrFail();
        $user->update([
            'status'    => 'approved',
            'is_active' => true,
        ]);

        return response()->json(['message' => 'User approved successfully.', 'user' => $this->formatUser($user)]);
    }

    /**
     * Reject a pending user.
     * POST /api/admin/pending-users/{id}/reject
     */
    public function reject(int $id)
    {
        $user = User::where('id', $id)->where('role', 'user')->firstOrFail();
        $user->update([
            'status'    => 'rejected',
            'is_active' => false,
        ]);

        return response()->json(['message' => 'User rejected.', 'user' => $this->formatUser($user)]);
    }

    private function formatUser($u): array
    {
        $proofUrl = null;
        if ($u->payment_proof) {
            $proofUrl = url('storage/' . $u->payment_proof);
        }

        return [
            'id'            => $u->id,
            'first_name'    => $u->first_name ?? '',
            'last_name'     => $u->last_name ?? '',
            'phone'         => $u->phone,
            'email'         => $u->email,
            'is_active'     => $u->is_active,
            'status'        => $u->status,
            'payment_proof' => $proofUrl,
            'created_at'    => $u->created_at->toDateTimeString(),
        ];
    }
}
