<?php

namespace App\Broadcasting;

use App\Helpers\WhatsappHelper;
use Illuminate\Notifications\Notification;

class WhatsappChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     * @return void
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        WhatsappHelper::send($notifiable->phone,$notifiable->name,$notification->toWhatsapp());
    }
}
