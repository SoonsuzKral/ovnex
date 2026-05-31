<?php
/*
 * OVNEX — Haber alındı event'i
 * Yeni haberler toplandığında WebSocket üzerinden yayın yapar
 */
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewsReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $news;

    public function __construct(array $news)
    {
        $this->news = $news;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('news.live');
    }

    public function broadcastAs(): string
    {
        return 'news.received';
    }
}
