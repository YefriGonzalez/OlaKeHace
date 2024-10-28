<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class RealTimeMessag extends Notification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public string $message;
    public $userId;
    public function __construct(string $message, int $userId)
    {
        $this->message = $message;
        $this->userId = $userId;
    }


    public function via($notifiable): array
    {
        return ['broadcast', 'database'];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => "$this->message (Message for $notifiable->name))",
            'userId' => $this->userId,
        ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => $this->message,
            'userId' => $this->userId,
        ];
    }
}
