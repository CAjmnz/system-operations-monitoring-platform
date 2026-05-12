<?php

namespace App\Http\Controllers;

use App\Models\SystemAlert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index()
    {
        return response()->json(
            SystemAlert::latest()->take(20)->get()
        );
    }

    public function resolve($id)
    {
        $alert = SystemAlert::findOrFail($id);

        $alert->update([
            'resolved' => true
        ]);

        return response()->json([
            'status' => 'resolved'
        ]);
    }
}