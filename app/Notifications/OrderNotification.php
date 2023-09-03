<?php

namespace App\Notifications;

use App\Broadcasting\WhatsappChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $url;
    protected $title;
    protected $message;
    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($url, $title, $message, $order)
    {
        $this->url = $url;
        $this->title = $title;
        $this->message = $message;
        $this->order = $order;
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
        $channels[] = "mail";
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

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->title)
            ->markdown('emails.order', [
                'order' => $this->order,
                'url' => $this->url,
                'title' => $this->title,
                'message' => $this->message,
            ]);
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
