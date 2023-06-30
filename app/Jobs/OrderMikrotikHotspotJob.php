<?php

namespace App\Jobs;

use App\Enums\BusinessCategoryEnum;
use App\Enums\OrderEnum;
use App\Enums\OrderMikrotikEnum;
use App\Models\Order;
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
                foreach($order->items as $index => $row){
                    if(!empty($row->order_mikrotik)){
                        if($row->order_mikrotik->type == OrderMikrotikEnum::TYPE_HOTSPOT){
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
