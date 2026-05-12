<?php

namespace App\Http\Controllers;

use App\Models\SystemLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        return response()->json([
            'logs' => SystemLog::latest()->take(50)->get()
        ]);
    }

    public function store(Request $request)
    {
        SystemLog::create([
            'user_id' => $request->user()?->id,
            'level' => $request->level ?? 'INFO',
            'message' => $request->message,
            'route' => $request->path(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['ok' => true]);
    }
}