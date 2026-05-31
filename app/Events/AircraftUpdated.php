<?php
/*
 * OVNEX — Uçak güncelleme event'i
 * Yeni uçak verisi toplandığında WebSocket üzerinden yayın yapar
 */
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AircraftUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $aircraft;

    public function __construct(array $aircraft)
    {
        $this->aircraft = $aircraft;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('aircraft.live');
    }

    public function broadcastAs(): string
    {
        return 'aircraft.updated';
    }
}
