<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usage;
use Illuminate\Support\Facades\Log;

class UsageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'total_seconds' => 'required|integer',
            'file_count'    => 'required|integer',
        ]);

        try {
            $user = $request->user();
            
            $usage = Usage::create([
                'user_id'       => $user->id,
                'total_seconds' => $request->total_seconds,
                'file_count'    => $request->file_count,
            ]);

            return response()->json([
                'success' => true,
                'usage'   => $usage
            ]);
        } catch (\Exception $e) {
            Log::error('Usage log error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to log usage'
            ], 500);
        }
    }
}
