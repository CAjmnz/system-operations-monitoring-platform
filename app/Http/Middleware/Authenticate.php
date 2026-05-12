<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Redirect unauthenticated users to welcome page (modal system)
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : '/';
    }
}