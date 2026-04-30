<?php

namespace App\System\Monitoring;

class SystemMetricService
{
    public function getCpuUsage()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return $this->getWindowsCpu();
        }

        $load = sys_getloadavg();
        return round($load[0] * 10, 2) . '%';
    }

    public function getMemoryUsage()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return $this->getWindowsMemory();
        }

        $free = shell_exec('free');
        if (!$free) return 'N/A';

        $lines = explode("\n", trim($free));
        $mem = preg_split('/\s+/', $lines[1]);

        $used = $mem[2] ?? 0;
        $total = $mem[1] ?? 1;

        return round(($used / $total) * 100, 2) . '%';
    }

    private function getWindowsCpu()
    {
        $output = shell_exec('wmic cpu get loadpercentage');
        preg_match('/\d+/', $output, $matches);
        return ($matches[0] ?? 0) . '%';
    }

    private function getWindowsMemory()
    {
        $output = shell_exec('wmic OS get FreePhysicalMemory,TotalVisibleMemorySize /Value');

        preg_match('/TotalVisibleMemorySize=(\d+)/', $output, $total);
        preg_match('/FreePhysicalMemory=(\d+)/', $output, $free);

        if (!isset($total[1]) || !isset($free[1])) {
            return 'N/A';
        }

        $used = $total[1] - $free[1];
        $percent = ($used / $total[1]) * 100;

        return round($percent, 2) . '%';
    }
}