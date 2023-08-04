<?php

namespace App\Helpers;

use App\Enums\DokuEnum;
use App\Enums\OrderEnum;
use App\Enums\ProviderEnum;
use App\Enums\RoleEnum;
use App\Enums\SettingCustomerFeeEnum;
use App\Enums\SettingFeeEnum;
use App\Enums\UserBankEnum;
use App\Models\SettingFee;
use App\Models\MikrotikConfig;
use App\Models\Order;
use App\Models\Provider;
use App\Models\SettingCustomerFee;
use App\Models\UserBank;
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
        $settingFee = $settingFee->orderBy("limit","ASC");
        $settingFee = $settingFee->get();

        return $settingFee;
    }

    public static function checkSettingFee($orderId){
        $return = [];
        try {
            $order = new Order();
            $order = $order->where("id",$orderId);
            $order = $order->first();

            $settingFee = self::settingFee();

            if(count($settingFee) <= 0){
                $return["IsError"] = TRUE;
                $return["Message"] = "Pengaturan fee pembayaran owner dan agen belum ditetapkan";
                goto ResultData;
            }

            $customer_type_fee = SettingCustomerFeeEnum::TYPE_PERCENTAGE;
            $customer_value_fee = 0;
            $customer_total_fee = 0;

            $owner_fee = 0;
            $agen_fee = 0;
            $total_owner_fee = 0;
            $total_agen_fee = 0;

            $doku_fee = 0;

            if($order->provider->type == ProviderEnum::TYPE_DOKU){

                if($order->doku_service_id == DokuEnum::SERVICE_EMONEY){
                    if($order->doku_channel_id == DokuEnum::CHANNEL_EMONEY_OVO){
                        $doku_fee = (1.5*$order->totalNeto())/100;
                        $doku_fee = $doku_fee + ((11*$doku_fee)/100);
                        $doku_fee = round($doku_fee);
                    }
                    else if($order->doku_channel_id == DokuEnum::CHANNEL_EMONEY_SHOPEEPAY){
                        $doku_fee = (2*$order->totalNeto())/100;
                        $doku_fee = $doku_fee + ((11*$doku_fee)/100);
                        $doku_fee = round($doku_fee);
                    }
                    else if($order->doku_channel_id == DokuEnum::CHANNEL_EMONEY_DOKU){
                        $doku_fee = (1.5*$order->totalNeto())/100;
                        $doku_fee = $doku_fee + ((11*$doku_fee)/100);
                        $doku_fee = round($doku_fee);
                    }
                }
                else if($order->doku_service_id == DokuEnum::SERVICE_VIRTUAL_ACCOUNT){
                    $doku_fee = 4500 + ((11*4500)/100);
                    $doku_fee = round($doku_fee);
                }
                else if($order->doku_service_id == DokuEnum::SERVICE_ONLINE_TO_OFFLINE){
                    $doku_fee = 5000 + ((11*5000)/100);
                    $doku_fee = round($doku_fee);
                }
                else if($order->doku_service_id == DokuEnum::CHANNEL_CREDIT_CARD){
                    $doku_fee = ((3 * $order->totalNeto())/100) + 2500;
                    $doku_fee = round($doku_fee);
                }
            }

            $settingCustomerFee = new SettingCustomerFee();
            $settingCustomerFee = $settingCustomerFee->orderBy("limit","ASC");
            $settingCustomerFee = $settingCustomerFee->get();

            foreach($settingCustomerFee as $index => $row){
                if($row->mark == SettingCustomerFeeEnum::MARK_KURANG_DARI){
                    if($order->totalNetoWithoutCustomerFee() <= $row->limit){
                        if($row->type == SettingCustomerFeeEnum::TYPE_PERCENTAGE){
                            $customer_total_fee = ($row->value/100) * $order->totalNetoWithoutCustomerFee();
                            $customer_total_fee = round($customer_total_fee);
                        }
                        else{
                            $customer_total_fee = $row->value;
                        }

                        $customer_type_fee = $row->type;
                        $customer_value_fee = $row->value;
                    }
                }else{
                    if($order->totalNetoWithoutCustomerFee() > $row->limit){
                        if($row->type == SettingCustomerFeeEnum::TYPE_PERCENTAGE){
                            $customer_total_fee = ($row->value/100) * $order->totalNetoWithoutCustomerFee();
                            $customer_total_fee = round($customer_total_fee);                  
                        }
                        else{
                            $customer_total_fee = $row->value;
                        }

                        $customer_type_fee = $row->type;
                        $customer_value_fee = $row->value;
                    }
                }
            }

            foreach($settingFee as $index => $row){
                if($row->mark == SettingFeeEnum::MARK_KURANG_DARI){
                    if($order->totalNeto() <= $row->limit){
                        $total_owner_fee = ($row->owner_fee/100) * $order->totalNeto();
                        $total_owner_fee = round($total_owner_fee);

                        $total_agen_fee = (($row->agen_fee/100) * $order->totalNeto());
                        $total_agen_fee = round($total_agen_fee);  

                        $owner_fee = $row->owner_fee;
                        $agen_fee = $row->agen_fee;
                    }
                }else{
                    if($order->totalNeto() > $row->limit){
                        $total_owner_fee = ($row->owner_fee/100) * $order->totalNeto();
                        $total_owner_fee = round($total_owner_fee);

                        $total_agen_fee = (($row->agen_fee/100) * $order->totalNeto());
                        $total_agen_fee = round($total_agen_fee);  

                        $owner_fee = $row->owner_fee;
                        $agen_fee = $row->agen_fee;
                    }
                }
            }

            if($order->status != OrderEnum::STATUS_SUCCESS){
                $total_agen_fee = 0;
            }

            $collection = [
                'owner_fee' => $owner_fee,
                'agen_fee' => $agen_fee,
                'total_owner_fee' => $total_owner_fee,
                'total_agen_fee' => $total_agen_fee,
                'customer_type_fee' => $customer_type_fee,
                'customer_value_fee' => $customer_value_fee,
                'customer_total_fee' => $customer_total_fee,
                'doku_fee' => $doku_fee,
            ];

            $return["IsError"] = FALSE;
            $return["Data"] = $collection;
            $return["Message"] = "Fee berhasil didapatkan";
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

    public static function hasBankActive(){
        $data = new UserBank();
        $data = $data->where("status",UserBankEnum::STATUS_APPROVED);
        $data = $data->where("default",UserBankEnum::DEFAULT_TRUE);

        if(Auth::user()->hasRole([RoleEnum::AGEN,RoleEnum::ADMIN_AGEN])){
            if(!empty(Auth::user()->business_id)){
                $data = $data->where("business_id",Auth::user()->business_id);
            }
        }

        if(Auth::user()->hasRole([RoleEnum::OWNER]) && empty(Auth::user()->business_id)){
            $data = $data->whereHas("user",function($query2){
                $query2->role([RoleEnum::OWNER]);
            });
        }

        if(Auth::user()->hasRole([RoleEnum::OWNER]) && !empty(Auth::user()->business_id)){
            $data = $data->where("business_id",Auth::user()->business_id);
        }

        $data = $data->get();

        if(count($data) >= 1){
            return true;
        }
        else{
            return false;
        }
    }

    public static function payLaterActive(){
        $data = new Provider();
        $data = $data->where("type",ProviderEnum::TYPE_PAY_LATER);
        $data = $data->where("status",ProviderEnum::STATUS_TRUE);
        $data = $data->first();

        if($data){
            return true;
        }
        else{
            return false;
        }
    }
}
