<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemUsage extends Model
{
    protected $fillable = [
        'user_id',
        'route',
        'method',
        'ip_address',
    ];
}