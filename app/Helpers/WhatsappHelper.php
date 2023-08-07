<?php

namespace App\Helpers;

use App\Enums\BusinessCategoryEnum;
use App\Enums\OrderEnum;
use App\Enums\OrderMikrotikEnum;
use App\Enums\ProviderEnum;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Log;

class WhatsappHelper
{
    public static function send($phone, $name, $content = [], bool $header = true)
    {
        try {
            $message = "";
            if ($header == true) {
                $message .= 'Hi *' . $name . "*,\r\n";
                $message .= "Pesan otomatis dari *www.bhillneka.com*\r\n\r\n";
            }
            $message .= $content['title'] . ".\r\n";
            $message .= ".\r\n";
            $message .= self::convertHtml($content['message']);

            $message .= "\r\n";
            $message .= "\r\n";
            $message .= "Penyedia Layanan / *www.bhillneka.com*";
            $message .= "\r\n";
            $message .= "TERIMAKASIH";
            $message .= "\r\n";

            $send = Http::timeout(60)->connectTimeout(60)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post(env("WHATSAPP_URL") . "/message", [
                'phoneNumber' => $phone,
                'message' => $message,
            ]);

            return $send;
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());
        }
    }

    public static function sendWhatsappOrderTemplate($orderId, string $type = "pesanan")
    {
        $order = new Order();
        $order = $order->where("id", $orderId);
        $order = $order->first();

        $message = "";
        if ($type == "pesanan") {
            if ($order->status == OrderEnum::STATUS_WAITING_PAYMENT) {
                $differenceDay = DateHelper::differentDay($order->created_at, date("d F Y", strtotime($order->expired_date)));
                if ($differenceDay == 7) {
                    $message .= "Jatuh tempo kurang dari 7 hari. ";
                } else if ($differenceDay == 5) {
                    $message .= "Jatuh tempo kurang dari 5 hari. ";
                } else if ($differenceDay == 2) {
                    $message .= "Jatuh tempo kurang dari 2 hari. ";
                }
                $message .= "Selesaikan Pembayaran Anda sebelum " . date("d F Y H:i:s", strtotime($order->expired_date)) . " WIB";
                $message .= "\r\n";
                $message .= "\r\n";
            }
            if ($order->status == OrderEnum::STATUS_EXPIRED) {
                $message .= "Maaf, pesanan dibatalkan. Anda telah mencapai batas pembayaran yang sudah ditentukan sampai tanggal " . date("d F Y H:i:s", strtotime($order->expired_date)) . " WIB";
                $message .= "\r\n";
                $message .= "\r\n";
            }
        } else if ($type == "progress") {
            $message = "Progress pesanan anda diubah menjadi *" . $order->progress()->msg . "*";
            $message .= "\r\n";
            $message .= "\r\n";
        }

        $message .= $order->business->name;
        $message .= "\r\n";
        $message .= $order->business->location;
        $message .= "\r\n";
        $message .= $order->business->user->phone;
        $message .= "\r\n";
        $message .= "=====";
        $message .= "\r\n";
        $message .= $order->status()->msg . " #" . $order->code;
        $message .= "\r\n";
        $message .= "=====";
        $message .= "\r\n";

        foreach ($order->items as $index => $row) {
            $message .= $row->product_name;
            $message .= "\r\n";
            $message .= $row->qty . " x " . number_format($row->product_price, 0, ',', '.') . " = " . number_format($row->totalBruto(), 0, ',', '.');
            if (!empty($row->order_mikrotik) && $order->business->category->name == BusinessCategoryEnum::MIKROTIK) {
                if ($order->status == OrderEnum::STATUS_SUCCESS) {
                    $message .= "\r\n";
                    if ($row->order_mikrotik->type == OrderMikrotikEnum::TYPE_HOTSPOT) {
                        $message .= "SSID : " . $row->order_mikrotik->server ?? null;
                        $message .= "\r\n";
                    }
                    $message .= "Username : " . $row->order_mikrotik->username ?? null;
                    $message .= "\r\n";
                    $message .= "Password : " . $row->order_mikrotik->password ?? null;
                }
            }
            $message .= "\r\n";
            $message .= "=====";
            $message .= "\r\n";
        }
        $message .= "Subtotal : " . number_format($order->subTotalItemBruto(), 0, ',', '.');
        $message .= "\r\n";
        $message .= "Discount : " . number_format($order->totalDiscount(), 0, ',', '.');
        $message .= "\r\n";
        $message .= "Biaya Layanan : " . number_format($order->customer_total_fee, 0, ',', '.');
        $message .= "\r\n";
        $message .= "Total : " . number_format($order->totalNeto(), 0, ',', '.');
        $message .= "\r\n";
        if ($order->status == OrderEnum::STATUS_SUCCESS) {
            $message .= "Bayar : " . number_format($order->totalNeto(), 0, ',', '.');
            $message .= "\r\n";
        } else {
            $message .= "Bayar : " . number_format(0, 0, ',', '.');
            $message .= "\r\n";
        }
        $message .= "Kembalian : " . number_format(0, 0, ',', '.');
        $message .= "\r\n";
        $message .= "\r\n";

        if ($order->status == OrderEnum::STATUS_WAITING_PAYMENT) {
            if ($order->provider->type == ProviderEnum::TYPE_DOKU) {
                $message .= "Link Pembayaran : " . $order->payment_url;
            } else if ($order->provider->type == ProviderEnum::TYPE_MANUAL_TRANSFER) {
                $message .= "Link Pembayaran : " . route('landing-page.manual-payments.index', $order->code);
            }
        } else if ($order->status == OrderEnum::STATUS_SUCCESS) {
            $message .= "Link Invoice : " . route('landing-page.orders.index', ['code' => $order->code]);
        } else {
            $message .= "Link Detail Pesanan : " . route('landing-page.orders.index', ['code' => $order->code]);
        }

        if (!empty($order->customer_id)) {
            return self::send($order->customer->phone, $order->customer->name, ["title" => "Notifikasi Pesanan", "message" => $message], true);
        } else {
            if (!empty($order->customer_name) && !empty($order->customer_phone)) {
                return self::send($order->customer_phone, $order->customer_name, ["title" => "Notifikasi Pesanan", "message" => $message], true);
            }
        }
    }

    private static function convertHtml(string $message = "")
    {
        $return = str_replace("<b>", "*", $message);
        $return = str_replace("</b>", "*", $return);

        return $return;
    }
}
