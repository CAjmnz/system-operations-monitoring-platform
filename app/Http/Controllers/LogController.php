<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class LogController extends Controller
{
    public function index()
    {
        Log::info('System log fetched');

        return response()->json([
            'logs' => [
                '[INFO] System running',
                '[INFO] CPU stable',
                '[WARNING] Memory usage moderate',
                '[INFO] Active users normal',
                '[INFO] System healthy',
            ]
        ]);
    }
}