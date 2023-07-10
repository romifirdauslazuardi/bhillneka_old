<?php

namespace App\Helpers;

use App\Enums\RoleEnum;
use App\Models\SettingFee;
use App\Models\MikrotikConfig;
use App\Services\UserService;
use App\Services\BusinessCategoryService;
use App\Settings\DashboardSetting;
use App\Settings\LandingPageSetting;
use Illuminate\Http\Request;
use Log;
use Auth;

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

    public static function userAgen(){
        $data = new UserService();
        $data = $data->index(new Request(['role' => RoleEnum::AGEN]),false);
        $data = $data->data;

        return $data;
    }

    public static function mikrotikConfig($business_id=null,$user_id=null){
        if(Auth::check()){
            if(!empty(Auth::user()->business_id)){
                $business_id = Auth::user()->business_id;
                $user_id = Auth::user()->business->user_id ?? null;
            }
        }
        $data = new MikrotikConfig();
        $data = $data->where("business_id",$business_id);
        $data = $data->where("user_id",$user_id);
        $data = $data->orderBy("created_at","DESC");
        $data = $data->first();

        return $data;
    }

    public static function businessCategories(){
        $data = new BusinessCategoryService();
        $data = $data->index(new Request([]),false);
        $data = $data->data;

        return $data;
    }
}
