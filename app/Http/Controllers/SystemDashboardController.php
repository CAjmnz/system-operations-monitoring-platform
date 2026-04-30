<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\System\Monitoring\SystemMetricService;

class SystemDashboardController extends Controller
{
    public function __construct(
        protected SystemMetricService $metrics
    ) {}

    public function index()
    {
        return response()->json([
            'server_status' => 'Online',
            'cpu_usage' => $this->metrics->getCpuUsage(),
            'memory_usage' => $this->metrics->getMemoryUsage(),
            'active_users' => 12,
            'alerts' => 'System Running Normally'
        ]);
    }
}

