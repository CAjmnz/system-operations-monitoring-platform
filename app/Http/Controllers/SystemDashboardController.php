<?php

namespace App\Http\Controllers;

use App\Models\SystemAlert;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SystemDashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MAIN DASHBOARD ENDPOINT
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        try {
            $cpu = $this->getCpuUsage();
            $memory = $this->getMemoryUsage();

            $this->maybeCreateAlert('CPU', $cpu);
            $this->maybeCreateAlert('MEMORY', $memory);

            return response()->json([
                'server_status' => $this->getServerStatus($cpu, $memory),
                'cpu_usage'     => $cpu,
                'memory_usage'  => $memory,
                'active_users'  => $this->getActiveUsers(),
                'alerts'        => $this->getAlerts(),
            ]);

        } catch (Throwable $e) {
            Log::error('SystemDashboard error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'server_status' => 'OFFLINE',
                'cpu_usage'     => 0,
                'memory_usage'  => 0,
                'active_users'  => 0,
                'alerts'        => [],
            ], 200);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ALERT SYSTEM
    |--------------------------------------------------------------------------
    */
    private function maybeCreateAlert(string $type, float $value, int $cooldown = 5)
    {
        if ($value >= 90) {
            $level = 'CRITICAL';
        } elseif ($value >= 70) {
            $level = 'WARNING';
        } else {
            SystemAlert::where('type', $type)
                ->where('is_resolved', false)
                ->update(['is_resolved' => true, 'resolved_at' => now()]);

            Cache::forget("alert_{$type}_CRITICAL");
            Cache::forget("alert_{$type}_WARNING");
            return;
        }

        $key = "alert_{$type}_{$level}";

        if (Cache::has($key)) {
            return;
        }

        SystemAlert::create([
            'type' => $type,
            'level' => $level,
            'message' => "{$type} usage at {$value}%",
        ]);

        Cache::put($key, true, now()->addMinutes($cooldown));
    }

    private function getAlerts(): array
    {
        return SystemAlert::where('is_resolved', false)
            ->latest()
            ->take(20)
            ->get()
            ->map(fn ($a) => [
                'id' => $a->id,
                'level' => $a->level,
                'type' => $a->type,
                'message' => $a->message,
                'created_at' => $a->created_at?->toDateTimeString(),
            ])
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | REAL CPU USAGE
    |--------------------------------------------------------------------------
    */
    private function getCpuUsage(): float
    {
        return Cache::remember('soc.cpu', 2, function () {

            if (PHP_OS_FAMILY === 'Linux' && function_exists('sys_getloadavg')) {
                $load = sys_getloadavg();
                $cores = (int) shell_exec('nproc') ?: 1;

                return round(($load[0] / $cores) * 100, 1);
            }

            // Windows fallback
            return 0.0;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | REAL MEMORY USAGE
    |--------------------------------------------------------------------------
    */
    private function getMemoryUsage(): float
    {
        return Cache::remember('soc.memory', 2, function () {

            if (PHP_OS_FAMILY === 'Linux') {
                $meminfo = @file_get_contents('/proc/meminfo');

                if ($meminfo) {
                    preg_match('/MemTotal:\s+(\d+)/', $meminfo, $total);
                    preg_match('/MemAvailable:\s+(\d+)/', $meminfo, $avail);

                    if (!empty($total[1]) && !empty($avail[1])) {
                        $used = $total[1] - $avail[1];
                        return round(($used / $total[1]) * 100, 1);
                    }
                }
            }

            return round(memory_get_usage(true) / 1024 / 1024, 1);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | SERVER STATUS
    |--------------------------------------------------------------------------
    */
    private function getServerStatus(float $cpu, float $memory): string
    {
        if ($cpu >= 90 || $memory >= 95) return 'OFFLINE';
        if ($cpu >= 70 || $memory >= 80) return 'DEGRADED';
        return 'ONLINE';
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE USERS
    |--------------------------------------------------------------------------
    */
    private function getActiveUsers(): int
    {
        try {
            if (config('session.driver') === 'database') {
                return DB::table('sessions')
                    ->where('last_activity', '>=', now()->subMinutes(5)->timestamp)
                    ->count();
            }

            return 0;
        } catch (Throwable) {
            return 0;
        }
    }
}