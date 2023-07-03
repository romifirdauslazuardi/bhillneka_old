<?php

namespace App\Notifications;

use App\Broadcasting\WhatsappChannel;
use App\Enums\OrderEnum;
use App\Enums\OrderMikrotikEnum;
use App\Enums\ProviderEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentNotification extends Notification implements ShouldQueue
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
            ->markdown('emails.payment', [
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
            'message' => self::sendWhatsapp($this->order),
        ];
    }

    private function sendWhatsapp($order){
        $message = "";
        $message .= "\r\n";
        $message .= "\r\n";
        $message .= $order->business->name;
        $message .= "\r\n";
        $message .= $order->business->location;
        $message .= "\r\n";
        $message .= $order->business->user->phone;
        $message .= "\r\n";
        $message .= "=====";
        $message .= "\r\n";
        $message .= $order->status()->msg." #".$order->code;
        $message .= "\r\n";
        $message .= "=====";
        $message .= "\r\n";
        foreach($order->items as $index => $row){
            $message .= $row->product_name;
            $message .= "\r\n";
            $message .= $row->qty." x ".number_format($row->product_price,0,',','.')." = ".number_format($row->totalNeto(),0,',','.');
            if($order->status == OrderEnum::STATUS_SUCCESS){
                $message .= "\r\n";
                if(!empty($row->order_mikrotik->mikrotik_id)){
                    if($row->order_mikrotik->type == OrderMikrotikEnum::TYPE_HOTSPOT){
                        $message .= "SSID : ".$row->order_mikrotik->server;
                        $message .= "\r\n";
                    }
                    $message .= "Username : ".$row->order_mikrotik->username;
                    $message .= "\r\n";
                    $message .= "Password : ".$row->order_mikrotik->password;
                }
            }
            $message .= "\r\n";
            $message .= "=====";
            $message .= "\r\n";
        }
        $message .= "Subtotal : ".number_format($order->totalNeto() + $order->discount,0,',','.');
        $message .= "\r\n";
        $message .= "Discount : ".number_format($order->discount,0,',','.');
        $message .= "\r\n";
        $message .= "Total : ".number_format($order->totalNeto(),0,',','.');
        $message .= "\r\n";
        if($order->status == OrderEnum::STATUS_SUCCESS){
            $message .= "Bayar : ".number_format($order->totalNeto(),0,',','.');
            $message .= "\r\n";
        }
        else{
            $message .= "Bayar : ".number_format(0,0,',','.');
            $message .= "\r\n";
        }
        $message .= "Kembalian : ".number_format(0,0,',','.');
        $message .= "\r\n";
        $message .= "\r\n";

        if($order->status == OrderEnum::STATUS_WAITING_PAYMENT){
            if($order->provider->type == ProviderEnum::TYPE_DOKU){
                $message .= "Link Pembayaran : ".$order->payment_url;
            }
            else if($order->provider->type == ProviderEnum::TYPE_MANUAL_TRANSFER){
                $message .= "Link Pembayaran : ".route('landing-page.manual-payments.index',$order->code);
            }
        }
        else{
            $message .= "Link Invoice : ".route('landing-page.orders.index',['code' => $order->code]);
        }

        $message .= "\r\n";
        $message .= "\r\n";

        $message .= "Penyedia Layanan / www.bhilnekka.com";
        $message .= "\r\n";
        $message .= "TERIMAKASIH";
        $message .= "\r\n";

        return $message;
    }
}
