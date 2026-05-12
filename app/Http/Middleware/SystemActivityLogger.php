<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;

class SystemActivityLogger
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {

            if (Auth::check()) {

                $user = Auth::user();

                Log::create([
                    'level' => 'INFO',
                    'message' =>
                        'User "' . $user->email .
                        '" accessed [' . $request->method() .
                        '] ' . $request->path()
                ]);
            }

        } catch (\Exception $e) {
            // silent fail
        }

        return $response;
    }
}