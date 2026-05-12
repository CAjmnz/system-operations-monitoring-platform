<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    protected $fillable = [
        'user_id',
        'level',
        'message',
        'route',
        'method',
        'ip_address',
    ];
}