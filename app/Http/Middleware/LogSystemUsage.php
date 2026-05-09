<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SystemUsage;

class LogSystemUsage
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        SystemUsage::create([
            'user_id' => optional($request->user())->id,
            'route' => $request->path(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
        ]);

        return $response;
    }
}