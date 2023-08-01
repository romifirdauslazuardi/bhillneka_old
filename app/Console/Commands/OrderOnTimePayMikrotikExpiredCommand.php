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
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderMikrotik;
use App\Models\ProductStock;
use App\Models\RouterosAPI;
use DB;
use Log;

class OrderOnTimePayMikrotikExpiredCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order-on-time-pay:expired-mikrotik';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Order On Time Pay Expired Mikrotik';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $orders = new Order();
            $orders = $orders->where("type",OrderEnum::TYPE_ON_TIME_PAY);
            $orders = $orders->where("status",OrderEnum::STATUS_SUCCESS);
            $orders = $orders->whereHas("business",function($query2){
                $query2->whereHas("category",function($query3){
                    $query3->where("name",BusinessCategoryEnum::MIKROTIK);
                });
            });
            $orders = $orders->orderBy("created_at","DESC");
            $orders = $orders->get();

            foreach($orders as $index => $order){
                
                foreach($order->items as $i => $value){
                    if(!empty($value->order_mikrotik->mikrotik_id)){
                        if(!empty($value->order_mikrotik->expired_date)){
                            if(date("Y-m-d",strtotime($value->order_mikrotik->expired_date." + 1 day")) == date("Y-m-d")){
                                if($value->order_mikrotik->type == OrderMikrotikEnum::TYPE_PPPOE){
                                    self::disabledMikrotik($value);
                                }
                            }
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
                    '.id' => $orderItem->order_mikrotik->mikrotik_id ?? null,
                ]);

                $connectLog = LogHelper::mikrotikLog($connect);

                if($connectLog["IsError"] == TRUE){
                    Log::emergency("OrderOnTimePayMikrotikExpiredCommand : ".$connectLog["Message"]. " #". $orderItem->order->code);
                }

                $orderItem->order_mikrotik()->update([
                    'disabled' => OrderMikrotikEnum::DISABLED_TRUE,
                ]);

                if($connectLog["IsError"] == FALSE){
                    $orderItem->order_mikrotik()->update([
                        'mikrotik_id' => null
                    ]);
                    
                    $message = "Maaf, penggunaan PPPOE sudah habis";
                    $message .= "\r\n";
                    $message .= "=====";
                    $message .= "\r\n";
                    $message .= "username = ".$orderItem->order_mikrotik->username ?? null;
                    $message .= "\r\n";
                    $message .= "password = ".$orderItem->order_mikrotik->password ?? null;
                    $message .= "\r\n";
                    $message .= "=====";
                    $message .= "\r\n";

                    if(!empty($orderItem->order->customer_id)){
                        return WhatsappHelper::send($orderItem->order->customer->phone,$orderItem->order->customer->name,["title" => "Penggunaan PPPOE Habis" ,"message" => $message],true);
                    }
                    else{
                        if(!empty($orderItem->order->customer_name) && !empty($orderItem->order->customer_phone)){
                            return WhatsappHelper::send($orderItem->order->customer_phone,$orderItem->order->customer_name,["title" => "Penggunaan PPPOE Habis" ,"message" => $message],true);
                        }
                    }
                }

            }
        }
    }
}
