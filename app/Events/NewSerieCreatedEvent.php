<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewSerieCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $serieName;
    public $seasonsQty;
    public $episodesQty;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($serieName, $seasonsQty, $episodesQty)
    {
        $this->serieName = $serieName;
        $this->seasonsQty = $seasonsQty;
        $this->episodesQty = $episodesQty;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
