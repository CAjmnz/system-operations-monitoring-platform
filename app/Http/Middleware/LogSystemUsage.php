<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SystemLog;

class LogSystemUsage
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->user()) {
            SystemLog::create([
                'user_id'    => $request->user()->id,
                'route'      => $request->path(),
                'method'     => $request->method(),
                'ip_address' => $request->ip(),
                'level'      => 'INFO',
                'message'    => 'User accessed ' . $request->path(),
            ]);
        }

        return $response;
    }
}