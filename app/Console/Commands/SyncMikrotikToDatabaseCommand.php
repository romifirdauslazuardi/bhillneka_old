<?php

namespace App\Console\Commands;

use App\Enums\BusinessCategoryEnum;
use App\Enums\OrderEnum;
use App\Enums\OrderMikrotikEnum;
use App\Helpers\SettingHelper;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;
use App\Models\Order;
use App\Models\RouterosAPI;
use DB;
use Log;

class SyncMikrotikToDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:mikrotik-to-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync mikrotik to database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $orders = new Order();
            $orders = $orders->where("status",OrderEnum::STATUS_SUCCESS);
            $orders = $orders->whereHas("business",function($query2){
                $query2->whereHas("category",function($query3){
                    $query3->where("name",BusinessCategoryEnum::MIKROTIK);
                });
            });
            $orders = $orders->orderBy("created_at","DESC");
            $orders = $orders->get();

            foreach($orders as $index => $order){
                
                foreach($order->items as $i => $orderItem){
                    if(!empty($orderItem->order_mikrotik->mikrotik_id)){
                        $mikrotikConfig = SettingHelper::mikrotikConfig($order->business_id,$order->business->user_id);
                        $ip = $mikrotikConfig->ip ?? null;
                        $username = $mikrotikConfig->username ?? null;
                        $password = $mikrotikConfig->password ?? null;
                        $port = $mikrotikConfig->port ?? null;
                        
                        $connect = new RouterosAPI();
                        $connect->debug("false");
        
                        if($connect->connect($ip,$username,$password,$port)){
                            if($orderItem->order_mikrotik->type == OrderMikrotikEnum::TYPE_PPPOE){
                                $connect = $connect->comm('/ppp/secret/print',[
                                    '?.id' => $orderItem->order_mikrotik->mikrotik_id
                                ]);
                            }
                            else{
                                $connect = $connect->comm('/ip/hotspot/user/print',[
                                    '?.id' => $orderItem->order_mikrotik->mikrotik_id
                                ]);
                            }
            
                            if(isset($connect[0]["disabled"])){
                                if($connect[0]["disabled"] == "false"){
                                    if($orderItem->order_mikrotik->disabled != OrderMikrotikEnum::DISABED_NO){
                                        $orderItem->order_mikrotik()->update([
                                            'disabled' => OrderMikrotikEnum::DISABED_NO
                                        ]);
                                    }
                                    
                                }
                                else if($connect[0]["disabled"] == "true"){
                                    if($orderItem->order_mikrotik->disabled != OrderMikrotikEnum::DISABLED_TRUE){
                                        $orderItem->order_mikrotik()->update([
                                            'disabled' => OrderMikrotikEnum::DISABLED_TRUE
                                        ]);
                                    }
                                }
                            }
                            else{
                                $orderItem->order_mikrotik()->update([
                                    'disabled' => OrderMikrotikEnum::DISABLED_TRUE
                                ]);
                            }
                        }
                    }
                    else{
                        if($orderItem->order_mikrotik->disabled != OrderMikrotikEnum::DISABLED_TRUE){
                            $orderItem->order_mikrotik()->update([
                                'disabled' => OrderMikrotikEnum::DISABLED_TRUE
                            ]);
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
}
