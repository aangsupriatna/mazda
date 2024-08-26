<?php

namespace App\Events;

use App\Models\Tampilan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TampilanEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tampilan;
    /**
     * Create a new event instance.
     */
    public function __construct(Tampilan $tampilan)
    {
        $this->tampilan = $tampilan;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('tampilan.' . $this->tampilan->id),
        ];
    }
}
