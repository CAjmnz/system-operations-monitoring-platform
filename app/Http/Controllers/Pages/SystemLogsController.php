<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class SystemLogsController extends Controller
{
    public function index()
    {
        return Inertia::render('SystemLogs');
    }
}
