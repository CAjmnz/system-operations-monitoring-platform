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
            return redirect('/');
        }

        // if usertype not allowed → block
        if (!in_array($user->usertype, $types)) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}