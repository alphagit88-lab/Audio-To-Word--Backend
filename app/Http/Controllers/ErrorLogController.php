<?php

namespace App\Http\Controllers;

use App\Models\ErrorLog;
use Illuminate\Http\Request;

class ErrorLogController extends Controller
{
    /**
     * Store a newly created error log in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'error_message' => 'required|string',
        ]);

        ErrorLog::create([
            'user_id' => $request->user()->id,
            'error_message' => $request->error_message,
        ]);

        return response()->json(['success' => true]);
    }
}
