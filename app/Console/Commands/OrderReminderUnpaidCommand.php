<?php

namespace App\Console\Commands;

use App\Enums\OrderEnum;
use App\Helpers\WhatsappHelper;
use Illuminate\Console\Command;
use App\Models\Order;
use Symfony\Component\Console\Command\Command as CommandAlias;
use DB;
use Log;

class OrderReminderUnpaidCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:reminder-unpaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Order Reminder Unpaid';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $orders = new Order();
            $orders = $orders->where("status",OrderEnum::STATUS_WAITING_PAYMENT);
            $orders = $orders->where("type",OrderEnum::TYPE_DUE_DATE);
            $orders = $orders->where("repeat_order_status",OrderEnum::REPEAT_ORDER_STATUS_TRUE);
            $orders = $orders->whereNotNull("order_id");
            $orders = $orders->get();

            foreach($orders as $index => $order){
                if(!empty($order->expired_date)){
                    $expiredDate = date("Y-m-d",strtotime($order->expired_date));

                    $dateMin5day = date("Y-m-d",strtotime($expiredDate." - 5 day"));
                    $dateMin2day = date("Y-m-d",strtotime($expiredDate." - 2 day"));

                    if($dateMin5day == date("Y-m-d") || $dateMin2day == date("Y-m-d") || $expiredDate == date("Y-m-d")){
                        WhatsappHelper::sendWhatsappOrderTemplate($order->id,"pesanan");
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
