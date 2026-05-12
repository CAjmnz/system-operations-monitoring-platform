<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemAlert extends Model
{
    protected $fillable = [
        'type',
        'level',
        'message',
        'is_resolved',
        'resolved_at',
    ];
}