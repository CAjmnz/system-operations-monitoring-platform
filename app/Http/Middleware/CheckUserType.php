<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    public function handle(Request $request, Closure $next, ...$types): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Unauthenticated');
        }

        // normalize usertype (VERY IMPORTANT FIX)
        $userType = strtolower(trim($user->usertype));

        // normalize allowed types
        $allowedTypes = array_map(fn($type) => strtolower(trim($type)), $types);

        if (!in_array($userType, $allowedTypes)) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}