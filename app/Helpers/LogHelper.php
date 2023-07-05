<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Str;
use Log;

class LogHelper
{
    public static function mikrotikLog($connect)
    {
        $data = [];
        if(isset($connect["!fatal"]) || isset($connect["!re"]) || isset($connect["!trap"]) || isset($connect["!done"])){
            if(isset($connect["!fatal"])){

                Log::emergency($connect["!fatal"][0]["message"]);

                $data["IsError"] = TRUE;
                $data["Message"] = $connect["!fatal"][0]["message"];
                goto ResultData;

            }
            if(isset($connect["!trap"])){
                
                Log::emergency($connect["!trap"][0]["message"]);

                $data["IsError"] = TRUE;
                $data["Message"] = $connect["!trap"][0]["message"];
                goto ResultData;

            }
            if(isset($connect["!re"])){
                
                Log::emergency($connect["!re"][0]["message"]);

                $data["IsError"] = TRUE;
                $data["Message"] = $connect["!re"][0]["message"];
                goto ResultData;
                
            }

            if(isset($connect["!done"])){
                
                Log::emergency($connect["!done"][0]["message"]);

                $data["IsError"] = TRUE;
                $data["Message"] = $connect["!done"][0]["message"];
                goto ResultData;

            }
        }

        if(is_array($connect) && count($connect) <= 0){
            $data["IsError"] = TRUE;
            $data["Message"] = "Data tidak ditemukan";
            goto ResultData;
        }

        $data["IsError"] = FALSE;
        $data["Message"] = "Success without error";
        $data["Data"] = $connect;
        goto ResultData;

        ResultData:
        return $data;
    }
}
