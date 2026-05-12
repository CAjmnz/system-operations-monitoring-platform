<?php

namespace App\Http\Controllers;

class SystemDashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'cpu_usage' => rand(10, 50),
            'memory_usage' => rand(10, 50),
            'status' => 'SAFE_MODE',
            'alerts' => [
                [
                    'type' => 'SYSTEM_SAFE',
                    'message' => 'System running safely (temporary mode)',
                    'level' => 'info'
                ]
            ],
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}