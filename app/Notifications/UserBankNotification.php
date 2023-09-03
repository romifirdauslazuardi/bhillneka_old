<?php

namespace App\Notifications;

use App\Broadcasting\WhatsappChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UserBankNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $url;

    protected string $title;

    protected string $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($url, $title, $message)
    {
        $this->url = $url;
        $this->title = $title;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(object $notifiable): array
    {
        $channels = [];
        $channels[] = "database";
        $channels[] = WhatsappChannel::class;

        return $channels;
    }

    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'title' => $this->title,
            'message' => $this->message,
        ];
    }

    public function toWhatsapp(): array
    {
        return [
            'url' => $this->url,
            'title' => $this->title,
            'message' => $this->message,
        ];
    }
}
