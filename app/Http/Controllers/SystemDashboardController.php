<?php

namespace App\Http\Controllers;

class SystemDashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'server_status' => 'Online',
            'cpu_usage' => rand(10, 80),
            'memory_usage' => rand(20, 90),
            'active_users' => rand(5, 30),
            'alerts' => 'System Running Normally',
        ]);
    }
}