<?php

namespace App\Services;

use App\Enums\DokuEnum;
use App\Enums\ProviderEnum;
use App\Enums\OrderEnum;
use App\Enums\RoleEnum;
use App\Models\Provider;
use App\Models\OrderDoku;
use App\Models\Order;
use App\Models\User;
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

    public function __construct()
    {
        $this->order = new Order();
        $this->orderDoku = new OrderDoku();
        $this->provider = new Provider();
        $this->user = new User();
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
                        $doku_fee = round(2 * $findOrder->totalNeto());
                    }
                    else if($findOrder->doku_service_id == DokuEnum::SERVICE_VIRTUAL_ACCOUNT){
                        $doku_fee = 4500 + round((11*4500)/100);
                    }
                    else if($findOrder->doku_service_id == DokuEnum::SERVICE_ONLINE_TO_OFFLINE){
                        $doku_fee = 5000 + round((11*5000)/100);
                    }

                    $findOrder->update([
                        'paid_date' => date("Y-m-d H:i:s"),
                        'doku_fee' => $doku_fee,
                    ]);
                    
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

                DB::commit();
                
                return $this->response(true, "Callback berhasil",null,Response::HTTP_OK);
            } else {

                DB::rollback();

                return $this->response(false, "Invalid Signature",null,Response::HTTP_BAD_REQUEST);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            Log::emergency($th->getMessage());
            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

}
