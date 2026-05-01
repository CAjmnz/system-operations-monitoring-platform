<?php

namespace App\Http\Controllers;

class LogController extends Controller
{
    public function index()
    {
        $logs = [
            ['level' => 'INFO', 'message' => 'System started successfully'],
            ['level' => 'WARNING', 'message' => 'CPU usage increasing'],
            ['level' => 'ERROR', 'message' => 'Disk read latency detected'],
            ['level' => 'INFO', 'message' => 'User login detected'],
        ];

        return response()->json([
            'logs' => $logs
        ]);
    }
}