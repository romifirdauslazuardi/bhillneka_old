<?php

namespace App\Console\Commands;

use App\Enums\BusinessCategoryEnum;
use App\Enums\OrderEnum;
use App\Enums\OrderMikrotikEnum;
use App\Enums\ProductEnum;
use App\Enums\ProviderEnum;
use App\Helpers\CodeHelper;
use App\Helpers\LogHelper;
use App\Helpers\PaymentHelper;
use App\Helpers\SettingHelper;
use App\Helpers\WhatsappHelper;
use App\Jobs\OrderJob;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderMikrotik;
use App\Models\ProductStock;
use App\Models\RouterosAPI;
use DB;
use Log;

class OrderMikrotikExpiredCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:expired-mikrotik';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Order Expired Mikrotik';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $orders = new Order();
            $orders = $orders->where("type",OrderEnum::TYPE_DUE_DATE);
            $orders = $orders->where("repeat_order_status",OrderEnum::REPEAT_ORDER_STATUS_TRUE);
            $orders = $orders->whereHas("business",function($query2){
                $query2->whereHas("category",function($query3){
                    $query3->where("name",BusinessCategoryEnum::MIKROTIK);
                });
            });
            $orders = $orders->orderBy("created_at","DESC");
            $orders = $orders->get();

            foreach($orders as $index => $order){
                
                $checkOtherOrder = new Order();
                $checkOtherOrder = $checkOtherOrder->where("order_id",$order->id);
                $checkOtherOrder = $checkOtherOrder->where("status",OrderEnum::STATUS_SUCCESS);
                $checkOtherOrder = $checkOtherOrder->orderBy("created_at","DESC");
                $checkOtherOrder = $checkOtherOrder->first();

                $ifOrderAgainSuccess = false;
                
                if($checkOtherOrder){
                    $dateCustomPlusDay = date("Y-m-d",strtotime(date($order->created_at)." + ".($order->repeat_order_at)." day"));

                    if(date("Y-m-d") <= $dateCustomPlusDay){
                        $ifOrderAgainSuccess = true;
                    }
                }

                if(!empty($order->repeat_order_at) && $ifOrderAgainSuccess == false){
                    $dateExpired = date("Y-m-d",strtotime(date($order->created_at)." + ".($order->repeat_order_at+1)." day"));

                    if($dateExpired <= date("Y-m-d")){
                        foreach($order->items as $i => $value){
                            self::disabledMikrotik($value);
                        }
                    }

                }
                
                if(empty($order->repeat_order_at) && $ifOrderAgainSuccess == false){
                    $dateExpired = date("Y-m-d",strtotime(date($order->created_at)." + 31 day"));

                    if($dateExpired <= date("Y-m-d")){
                        foreach($order->items as $i => $value){
                            self::disabledMikrotik($value);
                        }
                    }
                }
            }

            DB::commit();

            return CommandAlias::SUCCESS;

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::emergency($th->getMessage());
            return CommandAlias::FAILURE;
        }

        
    }

    private function disabledMikrotik($orderItem){
        $mikrotikConfig = SettingHelper::mikrotikConfig($orderItem->order->business_id ?? null,$orderItem->order->business->user_id ?? null);
        $ip = $mikrotikConfig->ip ?? null;
        $username = $mikrotikConfig->username ?? null;
        $password = $mikrotikConfig->password ?? null;
        $port = $mikrotikConfig->port ?? null;

        $connect = new RouterosAPI();
        $connect->debug("false");

        if(!$connect->connect($ip,$username,$password,$port)){
            Log::emergency('OrderMikrotikExpiredCommand : Koneksi dengan mikrotik gagal. Silahkan cek konfigurasi anda '.$orderItem->order->code);
        }

        if(!empty($orderItem->order_mikrotik->mikrotik_id)){
            if($orderItem->order_mikrotik->type == OrderMikrotikEnum::TYPE_PPPOE){
                $connect = $connect->comm('/ppp/secret/remove',[
                    '.id' => $orderItem->order_mikrotik->mirkotik_id ?? null,
                ]);

                $connectLog = LogHelper::mikrotikLog($connect);

                if($connectLog["IsError"] == TRUE){
                    Log::emergency("OrderMikrotikExpiredCommand : ".$connectLog["Message"]. " #". $orderItem->order->code);
                }

                $orderItem->order_mikrotik()->update([
                    'disabled' => OrderMikrotikEnum::DISABLED_TRUE,
                ]);

                if($connectLog["IsError"] == FALSE){
                    $orderItem->order_mikrotik()->update([
                        'mikrotik_id' => null
                    ]);
                }

            }
            else{

                $connect = $connect->comm('/ip/hotspot/user/remove',[
                    '.id' => $orderItem->order_mikrotik->mirkotik_id ?? null,
                ]);

                $connectLog = LogHelper::mikrotikLog($connect);

                if($connectLog["IsError"] == TRUE){
                    Log::emergency("OrderMikrotikExpiredCommand : ".$connectLog["Message"]. " #". $orderItem->order->code);
                }

                $orderItem->order_mikrotik()->update([
                    'disabled' => OrderMikrotikEnum::DISABLED_TRUE,
                ]);

                if($connectLog["IsError"] == FALSE){
                    $orderItem->order_mikrotik()->update([
                        'mikrotik_id' => null
                    ]);
                }
            }
        }
    }
}
