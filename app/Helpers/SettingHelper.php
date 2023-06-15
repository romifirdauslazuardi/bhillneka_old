<?php

namespace App\Helpers;

use App\Models\SettingFee;
use App\Settings\DashboardSetting;
use App\Settings\LandingPageSetting;
use Log;

class SettingHelper
{
    public static function settings(string $group, string $key)
    {
        return match ($group) {
            'dashboard' => app(DashboardSetting::class)->$key,
            'landing_page' => app(LandingPageSetting::class)->$key,
            default => null,
        };
    }

    public static function settingFee(){
        $settingFee = new SettingFee();
        $settingFee = $settingFee->orderBy("created_at","DESC");
        $settingFee = $settingFee->first();

        return $settingFee;
    }

    public static function checkSettingFee(){
        $return = [];
        try {
            $settingFee = new SettingFee();
            $settingFee = $settingFee->orderBy("created_at","DESC");
            $settingFee = $settingFee->first();

            if(!$settingFee){
                $return["IsError"] = TRUE;
                $return["Message"] = "Pengaturan fee pembayaran owner dan agen belum ditetapkan";
                goto ResultData;
            }

            if($settingFee->owner_fee <= 0){
                $return["IsError"] = TRUE;
                $return["Message"] = "Pengaturan fee pembayaran owner tidak valid";
                goto ResultData;
            }

            if($settingFee->agen_fee <= 0){
                $return["IsError"] = TRUE;
                $return["Message"] = "Pengaturan fee pembayaran agen tidak valid";
                goto ResultData;
            }

            $return["IsError"] = FALSE;
            $return["Data"] = $settingFee;
            $return["Message"] = "Pengaturan fee berhasil didapatkan";
            goto ResultData;
        }catch(\Throwable $th){
            Log::emergency($th->getMessage());
            $return["IsError"] = TRUE;
            $return["Message"] = $th->getMessage();
            goto ResultData;
        }

        ResultData:
        return $return;
    }
}
