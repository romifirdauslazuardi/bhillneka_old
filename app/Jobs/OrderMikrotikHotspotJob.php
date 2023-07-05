<?php

namespace App\Jobs;

use App\Enums\BusinessCategoryEnum;
use App\Enums\OrderEnum;
use App\Enums\OrderMikrotikEnum;
use App\Helpers\LogHelper;
use App\Helpers\SettingHelper;
use App\Models\Order;
use App\Models\RouterosAPI;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use DB;

class OrderMikrotikHotspotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::beginTransaction();
        try{
            $order = new Order();
            $order = $order->where('id', $this->id);
            $order = $order->whereHas("business",function($query2){
                $query2->where("category_id",BusinessCategoryEnum::MIKROTIK);
            });
            $order = $order->first();

            if($order){
                $mikrotikConfig = SettingHelper::mikrotikConfig($order->business_id,$order->business->user_id ?? null);
                $ip = $mikrotikConfig->ip ?? null;
                $username = $mikrotikConfig->username ?? null;
                $password = $mikrotikConfig->password ?? null;
                $port = $mikrotikConfig->port ?? null;

                $connect = new RouterosAPI();
                $connect->debug("false");

                if(!$connect->connect($ip,$username,$password,$port)){
                    Log::emergency('OrdferMikrotikHotspotJob : Koneksi dengan mikrotik gagal. Silahkan cek konfigurasi anda '.$order->code);
                }
                foreach($order->items as $index => $row){
                    if(!empty($row->order_mikrotik)){
                        if($row->order_mikrotik->type == OrderMikrotikEnum::TYPE_HOTSPOT){

                            if(!empty($row->order_mikrotik->mikrotik_id)){
                                $connect = $connect->comm('/ip/hotspot/user/remove',[
                                    '.id' => $row->order_mikrotik->mikrotik_id ?? null,
                                ]);
    
                                $connectLog = LogHelper::mikrotikLog($connect);
    
                                if($connectLog["IsError"] == TRUE){
                                    Log::emergency("OrderMikrotikExpiredCommand : ".$connectLog["Message"]. " #". $order->code);
                                }

                                if($connectLog["IsError"] == FALSE){
                                    $row->order_mikrotik()->update([
                                        'mikrotik_id' => null
                                    ]);
                                }
                            }

                            $row->order_mikrotik()->update([
                                'disabled' => OrderMikrotikEnum::DISABLED_TRUE,
                            ]);
                        }
                    }
                }
            }

            DB::commit();

       }catch(\Throwable $th){
            DB::rollBack();
            Log::emergency($th->getMessage());
       }
        
    }
}
