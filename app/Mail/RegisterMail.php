<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $settingFee;

    public function __construct($settingFee)
    {
        $this->settingFee = $settingFee;
    }

    public function build()
    {
        return $this->markdown('emails.register')
            ->subject('Pendaftaran Agen')
            ->with([
                'settingFee' => $this->settingFee,
            ]);
    }
}