<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $email;
    public $message;
    public $userName;
    public $subject;

    public function __construct($email, $message, $userName,$subject)
    {
        $this->email = $email;
        $this->message = $message;
        $this->userName = $userName;
        $this->subject = $subject;
    }

    public function build(): TicketMail
    {
        return $this->markdown('emails.ticket')
            ->replyTo($this->email)
            ->subject('Tiket Masuk')
            ->with([
                'message' => $this->message,
                'userName' => $this->userName,
                'email' => $this->email,
                'subject' => $this->subject,
            ]);
    }
}