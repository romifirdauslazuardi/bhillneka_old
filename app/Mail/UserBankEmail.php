<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserBankEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $title;
    public $message;
    public $url;

    public function __construct($user,$title,$message,$url)
    {
        $this->user = $user;
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
    }

    public function build()
    {
        return $this->markdown('emails.user-banks')
            ->subject('Aktivasi Rekening')
            ->with([
                'user' => $this->user,
                'title' => $this->title,
                'message' => $this->message,
                'url' => $this->url,
            ]);
    }
}