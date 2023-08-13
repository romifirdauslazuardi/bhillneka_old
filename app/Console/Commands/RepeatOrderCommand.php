<?php

namespace App\Console\Commands;

use App\Enums\BusinessCategoryEnum;
use App\Enums\OrderEnum;
use App\Enums\OrderMikrotikEnum;
use App\Enums\ProductEnum;
use App\Enums\ProductStockEnum;
use App\Enums\ProviderEnum;
use App\Helpers\CodeHelper;
use App\Helpers\PaymentHelper;
use App\Helpers\SettingHelper;
use App\Helpers\WhatsappHelper;
use App\Jobs\OrderExpiredJob;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderMikrotik;
use App\Models\ProductStock;
use DB;
use Log;

class RepeatOrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:repeat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Repeat order';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $orders = new Order();
            $orders = $orders->where("status",OrderEnum::STATUS_SUCCESS);
            $orders = $orders->where("type",OrderEnum::TYPE_DUE_DATE);
            $orders = $orders->where("repeat_order_status",OrderEnum::REPEAT_ORDER_STATUS_TRUE);
            $orders = $orders->whereNull("order_id");
            $orders = $orders->orderBy("created_at","DESC");
            $orders = $orders->get();

            foreach($orders as $index => $order){
                
                $generateOrder = null;

                $checkOtherOrder = new Order();
                $checkOtherOrder = $checkOtherOrder->where("order_id",$order->id);
                $checkOtherOrder = $checkOtherOrder->where("status","!=",OrderEnum::STATUS_SUCCESS);
                $checkOtherOrder = $checkOtherOrder->orderBy("created_at","DESC");
                $checkOtherOrder = $checkOtherOrder->count();

                if(empty($order->repeat_order_at) && $checkOtherOrder == 0){
                    $date30plusDay = date("Y-m-d",strtotime($order->created_at." + 30 day"));
                    $date7minDay = date("Y-m-d",strtotime($date30plusDay." -7 day"));

                    if($date7minDay == date("Y-m-d")){
                        $generateOrder = self::generateOrder($order);
                    }
                }
                
                if(!empty($order->repeat_order_at) && $checkOtherOrder == 0){
                    $dateRepeatAt = date("Y-m",strtotime($order->created_at." + 1 month"));
                    $dateRepeatAt = $dateRepeatAt."-".($order->repeat_order_at);
                    $date7minDay = date("Y-m-d",strtotime($dateRepeatAt." -7 day"));

                    if(date("Y-m-d") == $date7minDay){
                        $generateOrder = self::generateOrder($order);
                    }
                }

                if(!empty($generateOrder)){
                    $total = 0;
                    foreach($order->items as $i => $orderItem){

                        $dataOrderItem = [
                            'order_id' => $generateOrder->id,
                            'product_id' => $orderItem->product_id,
                            'product_code' => $orderItem->product_code,
                            'product_name' => $orderItem->product_name,
                            'product_price' => $orderItem->product_price,
                            'qty' => $orderItem->qty,
                            'discount' => $orderItem->discount,
                            'author_id' => $orderItem->author_id,
                        ];

                        $newOrderItem = OrderItem::updateOrCreate([
                            'order_id' => $generateOrder->id
                        ],$dataOrderItem);

                        if($orderItem->product->is_using_stock == ProductEnum::IS_USING_STOCK_TRUE){

                            $available = $orderItem->product->stocks()->where("type",ProductStockEnum::TYPE_MASUK)->sum("qty") - $orderItem->product->stocks()->where("type",ProductStockEnum::TYPE_KELUAR)->sum("qty");
                            $available -= $orderItem->qty;

                            ProductStock::create([
                                'date' => date("Y-m-d"),
                                'type' => ProductStockEnum::TYPE_KELUAR,
                                'product_id' => $orderItem->product_id,
                                'qty' => $orderItem->qty,
                                'available' => $available,
                                'note' => 'Stok keluar order #'.$generateOrder->code,
                                'author_id' => $orderItem->author_id,
                            ]);
                        }

                        $total += ( $orderItem->qty * $orderItem->product_price ) - $orderItem->discount; 
                        
                        if($generateOrder->business->category->name == BusinessCategoryEnum::MIKROTIK){
                            $dataOrderMikrotik = [
                                'order_item_id' => $newOrderItem->id,
                                'username' => $orderItem->order_mikrotik->username ?? null,
                                'profile' => $orderItem->order_mikrotik->profile ?? null,
                                'service' => $orderItem->order_mikrotik->service ?? null,
                                'server' => $orderItem->order_mikrotik->server ?? null,
                                'address' => $orderItem->order_mikrotik->address ?? null,
                                'mac_address' => $orderItem->order_mikrotik->mac_address ?? null,
                                'password' => $orderItem->order_mikrotik->password ?? null,
                                'type' => $orderItem->order_mikrotik->type ?? null,
                                'time_limit' => $orderItem->order_mikrotik->time_limit ?? null,
                                'comment' => $orderItem->order_mikrotik->comment ?? null,
                                'local_address' => $orderItem->order_mikrotik->local_address ?? null,
                                'remote_address' => $orderItem->order_mikrotik->remote_address ?? null,
                                'disabled' => OrderMikrotikEnum::DISABLED_TRUE,
                                'author_id' => $orderItem->order_mikrotik->author_id ?? null,
                            ];

                            if($orderItem->order_mikrotik->type == OrderMikrotikEnum::TYPE_PPPOE){
                                $dataOrderMikrotik = array_merge($dataOrderMikrotik,["type" => OrderMikrotikEnum::TYPE_PPPOE]);
                            }
                            else{
                                $dataOrderMikrotik = array_merge($dataOrderMikrotik,["type" => OrderMikrotikEnum::TYPE_HOTSPOT]);
                            }

                            OrderMikrotik::updateOrCreate([
                                'order_item_id' => $newOrderItem->id,
                            ],$dataOrderMikrotik);
                        }
                    }

                    $settingFee = SettingHelper::checkSettingFee($generateOrder->id);

                    if($settingFee["IsError"] == TRUE){
                        Log::emergency("RepeatOrderCommand : ".$settingFee["Message"]);
                    }
                    else{
                        $settingFee = $settingFee["Data"];

                        $generateOrder->update([
                            'doku_fee' => $settingFee["doku_fee"],
                            'owner_fee' => $settingFee["owner_fee"],
                            'agen_fee' => $settingFee["agen_fee"],
                            'total_owner_fee' => $settingFee["total_owner_fee"],
                            'total_agen_fee' => $settingFee["total_agen_fee"],
                            'customer_type_fee' => $settingFee["customer_type_fee"],
                            'customer_value_fee' => $settingFee["customer_value_fee"],
                            'customer_total_fee' => $settingFee["customer_total_fee"],
                        ]);
                    }

                    if($generateOrder->provider->type == ProviderEnum::TYPE_DOKU){
                        $checkoutDoku = PaymentHelper::checkoutDoku($generateOrder->id);

                        if($checkoutDoku["IsError"] == TRUE){
                            DB::rollback();
                            Log::emergency($checkoutDoku["Message"]);
                        }
                    }

                    OrderExpiredJob::dispatch($generateOrder->id)->delay(now()->addMinutes(10080+1));

                    WhatsappHelper::sendWhatsappOrderTemplate($generateOrder->id,"pesanan",true);
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

    private function generateOrder($order){
        $create = new Order();
        $create = $create->create([
            'code' => CodeHelper::generateOrder(),
            'user_id' => $order->user_id,
            'customer_id' => $order->customer_id,
            'customer_name' => $order->customer_name,
            'customer_phone' => $order->customer_phone,
            'customer_email' => $order->customer_email,
            'provider_id' => $order->provider_id,
            'note' => $order->note,
            'discount' => $order->discount,
            'status' => OrderEnum::STATUS_WAITING_PAYMENT,
            'progress' => OrderEnum::PROGRESS_DRAFT,
            'author_id' => $order->author_id,
            'expired_date' => date("YmdHis",strtotime(date("Y-m-d H:i:s")." + 7 day")),
            'business_id' => $order->business_id,
            'type' => $order->type,
            'fnb_type' => $order->fnb_type,
            'table_id' => $order->table_id,
            'repeat_order_at' => $order->repeat_order_at,
            'repeat_order_status' => $order->repeat_order_status,
            'order_id' => $order->id,
        ]);

        return $create;
    }
}
