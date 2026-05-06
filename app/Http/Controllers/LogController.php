<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;

class LogController extends Controller
{
    // GET logs
    public function index()
    {
        return response()->json([
            'logs' => Log::latest()->get()
        ]);
    }

    // POST log (manual/system logs)
    public function store(Request $request)
    {
        $request->validate([
            'level' => 'required',
            'message' => 'required'
        ]);

        Log::create([
            'level' => $request->level,
            'message' => $request->message
        ]);

        return response()->json([
            'status' => 'ok'
        ]);
    }
}