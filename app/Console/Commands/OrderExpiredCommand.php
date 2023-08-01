<?php

namespace App\Console\Commands;

use App\Enums\OrderEnum;
use App\Enums\ProductEnum;
use App\Enums\ProductStockEnum;
use App\Helpers\WhatsappHelper;
use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\ProductStock;
use Symfony\Component\Console\Command\Command as CommandAlias;
use DB;
use Log;

class OrderExpiredCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Order Expired Payment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $orders = new Order();
            $orders = $orders->where("status",OrderEnum::STATUS_WAITING_PAYMENT);
            $orders = $orders->get();

            foreach($orders as $index => $row){
                if(strtotime($row->expired_date) < strtotime(date("Y-m-d H:i:s"))){
                    $row->update([
                        'status' => OrderEnum::STATUS_EXPIRED,
                        'progress' => OrderEnum::PROGRESS_EXPIRED,
                    ]);
                }

                foreach($row->items as $i => $orderItem){
                    if($orderItem->product->is_using_stock == ProductEnum::IS_USING_STOCK_TRUE){
                        $available = $orderItem->product->stocks()->where("type",ProductStockEnum::TYPE_MASUK)->sum("qty") - $orderItem->product->stocks()->where("type",ProductStockEnum::TYPE_KELUAR)->sum("qty");

                        ProductStock::create([
                            'date' => date("Y-m-d"),
                            'type' => ProductStockEnum::TYPE_MASUK,
                            'product_id' => $orderItem->product_id,
                            'qty' => $orderItem->qty,
                            'available' => $available + $orderItem->qty,
                            'note' => 'Stok masuk order #'.$row->code,
                        ]);
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
