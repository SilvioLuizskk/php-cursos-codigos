<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class SettingsUpdated implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $settings;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        // Public channel for settings updates
        return new Channel('settings');
    }

    /**
     * Data to broadcast
     */
    public function broadcastWith()
    {
        return $this->settings;
    }
}
