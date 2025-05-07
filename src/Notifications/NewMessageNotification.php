<?php

namespace Namu\WireChat\Notifications;

use Carbon\Carbon;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Namu\WireChat\Facades\WireChat;
use Namu\WireChat\Models\Message;

class NewMessageNotification extends Notification implements ShouldBroadcastNow
{
    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via(object $notifiable): array
    {
        return ['broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message_id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
        ]);
    }
}
