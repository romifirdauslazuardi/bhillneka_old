<?php

namespace App\Console\Commands;

use App\Enums\OrderEnum;
use Illuminate\Console\Command;
use App\Models\Order;
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
                    ]);
                }
            }

            DB::commit();

            return CommandAlias::SUCCESS;

        } catch (\Throwable $th) {
            DB::rollback();
            Log::emergency($th->getMessage());
            return CommandAlias::FAILURE;
        }

        
    }
}
