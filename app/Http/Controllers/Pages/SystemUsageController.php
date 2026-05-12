<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\SystemUsage;
use Inertia\Inertia;

class SystemUsageController extends Controller
{
    public function index()
    {
        return Inertia::render('SystemUsage', [
            'totalRequests' => SystemUsage::count(),

            'latest' => SystemUsage::latest()
                ->take(20)
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