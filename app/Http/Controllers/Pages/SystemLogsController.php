<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\SystemUsage;
use Inertia\Inertia;

class SystemLogsController extends Controller
{
    public function index()
    {
        return Inertia::render('SystemLogs', [
            'logs' => SystemUsage::latest()
                ->take(50)
                ->get()
                ->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'method' => $log->method,
                        'route' => $log->route,
                        'ip_address' => $log->ip_address,
                        'created_at' => $log->created_at->toDateTimeString(),
                    ];
                }),
        ]);
    }
}