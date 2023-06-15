<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Log;

class WhatsappHelper
{
    public static function send($phone,$name,$content=[])
    {
        try {
            $message = 'Hi *'.$name."*,\r\n";
            $message .= "Ada pemberitahuan baru di *".config('app.name')."*\r\n\r\n";
            $message .= $content['title'].".\r\n";
            $message .= self::convertHtml($content['message']);

            Http::timeout(60)->connectTimeout(60)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post(env("WHATSAPP_URL")."/send-message",[
                'number' => $phone,
                'message' => $message,
            ]);

        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());
        }
    }

    private static function convertHtml(string $message = ""){
        $return = str_replace("<b>","*",$message);
        $return = str_replace("</b>","*",$return);

        return $return;
    }
}
