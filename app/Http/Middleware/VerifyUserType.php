<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyUserType
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
    
        if (!$user) {
            abort(403);
        }
    
        // USE ONLY role
        if (!in_array($user->role, $roles)) {
            abort(403, 'Access denied');
        }
    
        return $next($request);
    }
}