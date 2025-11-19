<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
class AppNotificationEvent implements ShouldBroadcast
{
    public $type;
    public $message;
    public $data;
    public $userId;

    public function __construct($type, $message, $data, $userId)
    {
        $this->type = $type;
        $this->message = $message;
        $this->data = $data;
        $this->userId = $userId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("notifications.{$this->userId}");
    }
}
