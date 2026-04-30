<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LogCreated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $log;

    public function __construct($log)
    {
        $this->log = $log;
    }

    public function broadcastOn()
    {
        return new Channel('system-logs');
    }
}