<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use App\Models\User;

class RealtimeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;
    public $sender;
    public $notificationId;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $message, User $sender)
    {
        $this->message = $message;
        $this->sender = $sender;
        $this->notificationId = Str::uuid()->toString();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'id' => $this->notificationId,
            'message' => $this->message,
            'time' => now()->toDateTimeString(),
            'sender' => [
                'id' => $this->sender->id,
                'name' => $this->sender->first_name . ' ' . $this->sender->last_name,
            ],
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id' => $this->notificationId,
            'message' => $this->message,
            'time' => now()->toDateTimeString(),
            'sender' => [
                'id' => $this->sender->id,
                'name' => $this->sender->first_name . ' ' . $this->sender->last_name
            ]
        ]);
    }
}
