<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;

class SystemDashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // CPU usage — read from /proc/stat on Linux, fallback on Windows
        $cpu = $this->getCpuUsage();

        // Memory usage
        $memory = $this->getMemoryUsage();

        // Active users = users seen in last 5 minutes
        $activeUsers = User::where('last_seen_at', '>=', now()->subMinutes(5))->count();

        return response()->json([
            'server_status' => 'Online',
            'cpu_usage'     => $cpu,
            'memory_usage'  => $memory,
            'active_users'  => $activeUsers,
            'alerts'        => 'None',
        ]);
    }

    private function getCpuUsage(): float
    {
        // Windows-safe fallback
        if (PHP_OS_FAMILY === 'Windows') {
            $output = shell_exec('wmic cpu get loadpercentage /value 2>nul');
            if ($output && preg_match('/LoadPercentage=(\d+)/', $output, $m)) {
                return (float) $m[1];
            }
            return 0.0;
        }

        // Linux
        $load = sys_getloadavg();
        return round($load[0] * 100 / max(1, (int) shell_exec('nproc')), 1);
    }

    private function getMemoryUsage(): float
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $total = shell_exec('wmic OS get TotalVisibleMemorySize /value 2>nul');
            $free  = shell_exec('wmic OS get FreePhysicalMemory /value 2>nul');
            if (
                preg_match('/TotalVisibleMemorySize=(\d+)/', $total, $tm) &&
                preg_match('/FreePhysicalMemory=(\d+)/', $free, $fm)
            ) {
                $used = $tm[1] - $fm[1];
                return round(($used / $tm[1]) * 100, 1);
            }
            return 0.0;
        }

        // Linux
        $meminfo = file_get_contents('/proc/meminfo');
        preg_match('/MemTotal:\s+(\d+)/', $meminfo, $total);
        preg_match('/MemAvailable:\s+(\d+)/', $meminfo, $avail);
        $used = $total[1] - $avail[1];
        return round(($used / $total[1]) * 100, 1);
    }
}