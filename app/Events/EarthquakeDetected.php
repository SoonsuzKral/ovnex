<?php
/*
 * OVNEX — Deprem tespit event'i
 * 4.0 ve üzeri depremlerde bildirim yayını yapar
 */
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EarthquakeDetected implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $earthquake;

    public function __construct(array $earthquake)
    {
        $this->earthquake = $earthquake;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('earthquake.alerts');
    }

    public function broadcastAs(): string
    {
        return 'earthquake.detected';
    }
}
