<?php

namespace App\Services;

use App\Enums\BusinessCategoryEnum;
use App\Enums\DokuEnum;
use App\Enums\ProviderEnum;
use App\Enums\OrderEnum;
use App\Enums\OrderMikrotikEnum;
use App\Enums\RoleEnum;
use App\Helpers\DateHelper;
use App\Helpers\LogHelper;
use App\Helpers\SettingHelper;
use App\Helpers\WhatsappHelper;
use App\Jobs\OrderMikrotikHotspotJob;
use App\Models\Provider;
use App\Models\OrderDoku;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\RouterosAPI;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Notifications\PaymentNotification;
use Auth;
use DB;
use Log;
use Throwable;
use Notification;

class CallbackService extends BaseService
{
    protected $order;
    protected $orderDoku;
    protected $provider;
    protected $user;
    protected $routerosApi;

    public function __construct()
    {
        $this->order = new Order();
        $this->orderDoku = new OrderDoku();
        $this->provider = new Provider();
        $this->user = new User();
        $this->routerosApi = new RouterosAPI();
    }

    public function doku(Request $request) {
        DB::beginTransaction();
        try {
            $providerDoku = $this->provider;
            $providerDoku = $providerDoku->where("status",ProviderEnum::STATUS_TRUE);
            $providerDoku = $providerDoku->where("type",ProviderEnum::TYPE_DOKU);
            $providerDoku = $providerDoku->first();

            $notificationHeader = getallheaders();
            $notificationBody = file_get_contents('php://input');
            $notificationPath = '/api/payments/notifications'; // Adjust according to your notification path
            $secretKey = $providerDoku->secret_key ?? null; // Adjust according to your secret key
        
            $digest = base64_encode(hash('sha256', $notificationBody, true));
            $rawSignature = "Client-Id:" . $notificationHeader['Client-Id'] . "\n"
                . "Request-Id:" . $notificationHeader['Request-Id'] . "\n"
                . "Request-Timestamp:" . $notificationHeader['Request-Timestamp'] . "\n"
                . "Request-Target:" . $notificationPath . "\n"
                . "Digest:" . $digest;
        
            $signature = base64_encode(hash_hmac('sha256', $rawSignature, $secretKey, true));
            $finalSignature = 'HMACSHA256=' . $signature;

            $decode = json_decode($notificationBody,true);
        
            if ($finalSignature == $notificationHeader['Signature']) {
                $findOrder = $this->order;
                $findOrder = $findOrder->where("code",$decode["order"]["invoice_number"]);
                $findOrder = $findOrder->first();
                
                $findOrder->update([
                    'status' => $decode["transaction"]["status"],
                    'doku_service_id' => $decode["service"]["id"],
                    'doku_acquirer_id' => $decode["acquirer"]["id"],
                    'doku_channel_id' => $decode["channel"]["id"],
                ]);

                if($decode["transaction"]["status"] == OrderEnum::STATUS_SUCCESS){

                    $doku_fee = 0;

                    if($findOrder->doku_service_id == DokuEnum::SERVICE_EMONEY){
                        if($findOrder->doku_channel_id == DokuEnum::CHANNEL_EMONEY_OVO){
                            $doku_fee = (1.5*$findOrder->totalNeto())/100;
                            $doku_fee = $doku_fee + ((11*$doku_fee)/100);
                            $doku_fee = round($doku_fee);
                        }
                        else if($findOrder->doku_channel_id == DokuEnum::CHANNEL_EMONEY_SHOPEEPAY){
                            $doku_fee = (2*$findOrder->totalNeto())/100;
                            $doku_fee = $doku_fee + ((11*$doku_fee)/100);
                            $doku_fee = round($doku_fee);
                        }
                        else if($findOrder->doku_channel_id == DokuEnum::CHANNEL_EMONEY_DOKU){
                            $doku_fee = (1.5*$findOrder->totalNeto())/100;
                            $doku_fee = $doku_fee + ((11*$doku_fee)/100);
                            $doku_fee = round($doku_fee);
                        }
                    }
                    else if($findOrder->doku_service_id == DokuEnum::SERVICE_VIRTUAL_ACCOUNT){
                        $doku_fee = 4500 + ((11*4500)/100);
                        $doku_fee = round($doku_fee);
                    }
                    else if($findOrder->doku_service_id == DokuEnum::SERVICE_ONLINE_TO_OFFLINE){
                        $doku_fee = 5000 + ((11*5000)/100);
                        $doku_fee = round($doku_fee);
                    }
                    else if($findOrder->doku_service_id == DokuEnum::CHANNEL_CREDIT_CARD){
                        $doku_fee = ((3 * $findOrder->totalNeto())/100) + 2500;
                        $doku_fee = round($doku_fee);
                    }

                    $findOrder->update([
                        'paid_date' => date("Y-m-d H:i:s"),
                        'doku_fee' => $doku_fee,
                    ]);

                    if($findOrder->business->category->name == BusinessCategoryEnum::MIKROTIK){

                        $mikrotikConfig = SettingHelper::mikrotikConfig($findOrder->business_id,$findOrder->business->user_id);
                        $ip = $mikrotikConfig->ip ?? null;
                        $username = $mikrotikConfig->username ?? null;
                        $password = $mikrotikConfig->password ?? null;
                        $port = $mikrotikConfig->port ?? null;

                        foreach($findOrder->items as $index => $row){
                            $connect = $this->routerosApi;
                            $connect->debug("false");
                            
                            if(!$connect->connect($ip,$username,$password,$port)){
                                Log::emergency('Callback : Koneksi dengan mikrotik gagal. Silahkan cek konfigurasi anda');
                            }

                            if(!empty($row->order_mikrotik)){
                                if($row->order_mikrotik->type == OrderMikrotikEnum::TYPE_PPPOE){
                                    $connectData = [
                                        'name' => $row->order_mikrotik->username ?? null,
                                        'password' => $row->order_mikrotik->password ?? null,
                                        'service' => $row->order_mikrotik->service ?? null,
                                        'profile' => $row->order_mikrotik->profile ?? null,
                                        'local-address' => $row->order_mikrotik->local_address ?? null,
                                        'remote-address' => $row->order_mikrotik->remote_address ?? null,
                                        'comment' => $row->order_mikrotik->comment ?? null,
                                        'disabled' => OrderMikrotikEnum::DISABED_NO,
                                    ];

                                    if(!empty($row->order_mikrotik->mikrotik_id)){
                                        $connectData = array_merge($connectData,[".id" => $row->order_mikrotik->mikrotik_id]);
                                        $connect = $connect->comm('/ppp/secret/set',$connectData);
                                    }
                                    else{
                                        $connect = $connect->comm('/ppp/secret/add',$connectData);
                                    }
                
                                    $connectLog = LogHelper::mikrotikLog($connect);

                                    if($connectLog["IsError"] == TRUE){
                                        Log::emergency("Callback : ".$connectLog["Message"]);
                                    }

                                    $row->order_mikrotik()->update([
                                        'disabled' => OrderMikrotikEnum::DISABED_NO,
                                    ]);

                                    if(empty($row->order_mikrotik->mikrotik_id)){
                                        if($connectLog["IsError"] == FALSE){
                                            $row->order_mikrotik()->update([
                                                'mikrotik_id' => $connect
                                            ]);
                                        }
                                    }
                                }
                                else{
                                    $connectData = [
                                        'name' => $row->order_mikrotik->username ?? null,
                                        'password' => $row->order_mikrotik->password ?? null,
                                        'server' => $row->order_mikrotik->server ?? null,
                                        'profile' => $row->order_mikrotik->profile ?? null,
                                        'limit-uptime' => $row->order_mikrotik->time_limit ?? null,
                                        'comment' => $row->order_mikrotik->comment ?? null,
                                        'disabled' => OrderMikrotikEnum::DISABED_NO,
                                    ];
            
                                    if(!empty($row->order_mikrotik->address)){
                                        $connectData = array_merge($connectData,["address" => $row->order_mikrotik->address]);
                                    }
            
                                    if(!empty($row->order_mikrotik->mac_address)){
                                        $connectData = array_merge($connectData,["mac-address" => $row->order_mikrotik->mac_address]);
                                    }

                                    if(!empty($row->order_mikrotik->mikrotik_id)){
                                        $connectData = array_merge($connectData,[".id" => $row->order_mikrotik->mikrotik_id]);
                                        $connect = $connect->comm('/ip/hotspot/user/set',$connectData);
                                    }
                                    else{
                                        $connect = $connect->comm('/ip/hotspot/user/add',$connectData);
                                    }

                                    $connectLog = LogHelper::mikrotikLog($connect);

                                    if($connectLog["IsError"] == TRUE){
                                        Log::emergency("Callback : ".$connectLog["Message"]);
                                    }

                                    $row->order_mikrotik()->update([
                                        'disabled' => OrderMikrotikEnum::DISABED_NO,
                                    ]);

                                    if(empty($row->order_mikrotik->mikrotik_id)){
                                        if($connectLog["IsError"] == FALSE){
                                            $row->order_mikrotik()->update([
                                                'mikrotik_id' => $connect
                                            ]);
                                        }
                                    }

                                    $explodeTimeLimit = str_split($row->order_mikrotik->time_limit);

                                    $totalSecond = 0;
                                    foreach($explodeTimeLimit as $i => $value){
                                        if(isset($explodeTimeLimit[$i+1])){
                                            if(strtolower($explodeTimeLimit[$i+1]) == "d"){
                                                $totalSecond += (((int)$value) * 24 * 60 * 60);
                                            }
                                            if(strtolower($explodeTimeLimit[$i+1]) == "h"){
                                                $totalSecond += (((int)$value) * 60 * 60);
                                            }
                                            if(strtolower($explodeTimeLimit[$i+1]) == "m"){
                                                $totalSecond += (((int)$value) * 60);
                                            }
                                            if(strtolower($explodeTimeLimit[$i+1]) == "s"){
                                                $totalSecond += (((int)$value));
                                            }
                                        }
                                    }

                                    OrderMikrotikHotspotJob::dispatch($findOrder->id)->delay(now()->addSeconds($totalSecond));
                                }
                            }
                        }
                    }
                    
                }

                $orderDoku = $this->orderDoku;
                $orderDoku = $orderDoku->create([
                    'order_id' => $findOrder->id,
                    'target' => '/api/payments/notifications',
                    'data' => $decode
                ]);

                $received = $this->user;
                $received = $received->where(function($query2) use($findOrder){
                    $query2->role([RoleEnum::OWNER]);
                    $query2->orWhere('id',$findOrder->user_id);
                    $query2->orWhere('id',$findOrder->author_id);
                });
                $received = $received->get();

                Notification::send($received,new PaymentNotification(route('dashboard.orders.show',$findOrder->id),'Pembayaran Pesanan','Pembayaran dengan kode transaksi '.$findOrder->code." sebesar <b>".number_format($findOrder->totalNeto(),0,',','.')."</b> telah <b>[".$decode["transaction"]["status"]."</b>] dilakukan.",$findOrder));

                self::sendWhatsapp($findOrder->id);

                DB::commit();
                
                return $this->response(true, "Callback berhasil",null,Response::HTTP_OK);
            } else {

                DB::rollBack();

                return $this->response(false, "Invalid Signature",null,Response::HTTP_BAD_REQUEST);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::emergency($th->getMessage());
            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    private function sendWhatsapp($orderId,string $type = "pesanan"){
        $order = $this->order;
        $order = $order->where("id",$orderId);
        $order = $order->first();

        $message = "";
        if($type == "pesanan"){
            if($order->status == OrderEnum::STATUS_WAITING_PAYMENT){
                $message .= "Selesaikan Pembayaran Anda sebelum ".date("d F Y H:i:s",strtotime($order->expired_date))." WIB";
                $message .= "\r\n";
            }
        }
        else if($type == "progress"){
            $message = "Progress pesanan anda diubah menjadi *".$order->progress()->msg."*";
            $message .= "\r\n";
        }
        
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
            if($order->status == OrderEnum::STATUS_SUCCESS){
                $message .= "\r\n";
                if($row->order_mikrotik->type == OrderMikrotikEnum::TYPE_HOTSPOT){
                    $message .= "SSID : ".$row->order_mikrotik->server ?? null;
                    $message .= "\r\n";
                }
                $message .= "Username : ".$row->order_mikrotik->username ?? null;
                $message .= "\r\n";
                $message .= "Password : ".$row->order_mikrotik->password ?? null;
            }
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
            return WhatsappHelper::send($order->customer->phone,$order->customer->name,["title" => "Notifikasi Pesanan" ,"message" => $message],true);
        }
        else{
            if(!empty($order->customer_name) && !empty($order->customer_phone)){
                return WhatsappHelper::send($order->customer_phone,$order->customer_name,["title" => "Notifikasi Pesanan" ,"message" => $message],true);
            }
        }
    }

}
