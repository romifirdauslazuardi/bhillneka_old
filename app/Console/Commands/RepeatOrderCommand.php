<?php

namespace App\Console\Commands;

use App\Enums\BusinessCategoryEnum;
use App\Enums\OrderEnum;
use App\Enums\OrderMikrotikEnum;
use App\Enums\ProductEnum;
use App\Enums\ProviderEnum;
use App\Helpers\CodeHelper;
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
                    $date30plusDay = date("Y-m-d",strtotime($order->created_at."+ 30 day"));

                    if($date30plusDay == date("Y-m-d")){
                        $generateOrder = self::generateOrder($order);
                    }
                }
                
                if(!empty($order->repeat_order_at) && $checkOtherOrder == 0){
                    if(((int)date("d")) == $order->repeat_order_at){
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
                            ProductStock::create([
                                'product_id' => $orderItem->product_id,
                                'qty' => -$orderItem->qty,
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
                                $dataOrderMikrotik["type"] = OrderMikrotikEnum::TYPE_PPPOE;
                            }
                            else{
                                $dataOrderMikrotik["type"] = OrderMikrotikEnum::TYPE_HOTSPOT;
                            }

                            OrderMikrotik::updateOrCreate([
                                'order_item_id' => $newOrderItem->id,
                            ],$dataOrderMikrotik);
                        }
                    }

                    $settingFee = SettingHelper::settingFee();

                    $generateOrder->update([
                        'owner_fee' => $settingFee->owner_fee ?? null,
                        'agen_fee' => $settingFee->agen_fee ?? null,
                    ]);

                    if($generateOrder->provider->type == ProviderEnum::TYPE_DOKU){
                        $checkoutDoku = PaymentHelper::checkoutDoku($generateOrder);

                        if($checkoutDoku["IsError"] == TRUE){
                            DB::rollback();
                            Log::emergency($checkoutDoku["Message"]);
                        }
                    }

                    OrderJob::dispatch($generateOrder->id)->delay(now()->addMinutes(env("DOKU_DUE_DATE")));

                    self::sendWhatsapp($generateOrder,"pesanan");
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
            'provider_id' => $order->provider_id,
            'note' => $order->note,
            'discount' => $order->discount,
            'status' => OrderEnum::STATUS_WAITING_PAYMENT,
            'progress' => OrderEnum::PROGRESS_DRAFT,
            'author_id' => $order->author_id,
            'expired_date' => date("YmdHis",strtotime(date("Y-m-d H:i:s")." + ".env("DOKU_DUE_DATE")."minutes")),
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

    private function sendWhatsapp($order,string $type = "pesanan"){
        $message = "";
        if($type == "pesanan"){
            $message .= "Selesaikan Pembayaran Anda sebelum ".date("d F Y H:i:s",strtotime($order->expired_date))." WIB";
        }
        else if($type == "progress"){
            $message = "Progress pesanan anda diubah menjadi *".$order->progress()->msg."*";
        }
        
        $message .= "\r\n";
        $message .= "\r\n";
        $message .= $order->business->name;
        $message .= "\r\n";
        $message .= $order->business->location;
        $message .= "\r\n";
        $message .= $order->business->user->phone;
        $message .= "\r\n";
        $message .= "=====";
        $message .= "\r\n";
        $message .= $order->status()->msg." #".$order->code;
        $message .= "\r\n";
        $message .= "=====";
        $message .= "\r\n";
        foreach($order->items as $index => $row){
            $message .= $row->product_name;
            $message .= "\r\n";
            $message .= $row->qty." x ".number_format($row->product_price,0,',','.')." = ".number_format($row->totalNeto(),0,',','.');
            $message .= "\r\n";
            $message .= "=====";
            $message .= "\r\n";
        }
        $message .= "Subtotal : ".number_format($order->totalNeto() + $order->discount,0,',','.');
        $message .= "\r\n";
        $message .= "Discount : ".number_format($order->discount,0,',','.');
        $message .= "\r\n";
        $message .= "Total : ".number_format($order->totalNeto(),0,',','.');
        $message .= "\r\n";
        if($order->status == OrderEnum::STATUS_SUCCESS){
            $message .= "Bayar : ".number_format($order->totalNeto(),0,',','.');
            $message .= "\r\n";
        }
        else{
            $message .= "Bayar : ".number_format(0,0,',','.');
            $message .= "\r\n";
        }
        $message .= "Kembalian : ".number_format(0,0,',','.');
        $message .= "\r\n";
        $message .= "\r\n";

        if($order->status == OrderEnum::STATUS_WAITING_PAYMENT){
            if($order->provider->type == ProviderEnum::TYPE_DOKU){
                $message .= "Link Pembayaran : ".$order->payment_url;
            }
            else if($order->provider->type == ProviderEnum::TYPE_MANUAL_TRANSFER){
                $message .= "Link Pembayaran : ".route('landing-page.manual-payments.index',$order->code);
            }
        }
        else{
            $message .= "Link Invoice : ".route('landing-page.orders.index',['code' => $order->code]);
        }

        $message .= "\r\n";
        $message .= "\r\n";

        $message .= "Penyedia Layanan / www.bhilnekka.com";
        $message .= "\r\n";
        $message .= "TERIMAKASIH";
        $message .= "\r\n";

        if(!empty($order->customer_id)){
            return WhatsappHelper::send($order->customer->phone,$order->customer->name,["title" => "Notifikasi Pesanan" ,"message" => $message],false);
        }
        else{
            if(!empty($order->customer_name) && !empty($order->customer_phone)){
                return WhatsappHelper::send($order->customer_phone,$order->customer_name,["title" => "Notifikasi Pesanan" ,"message" => $message],false);
            }
        }
    }
}
