<?php

namespace App\Helpers;

use App\Enums\OrderEnum;
use App\Enums\ProviderEnum;
use App\Enums\RoleEnum;
use App\Enums\UserBankEnum;
use App\Models\Order;
use App\Models\Provider;
use App\Models\OrderDoku;
use App\Models\UserBank;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Log;
use DateTime;

class PaymentHelper
{
    public static function generateSignature(array $requestBody = [])
    {
        $return = [];
        try {
            $providerDoku = new Provider();
            $providerDoku = $providerDoku->where("status",ProviderEnum::STATUS_TRUE);
            $providerDoku = $providerDoku->where("type",ProviderEnum::TYPE_DOKU);
            $providerDoku = $providerDoku->first();

            if(!$providerDoku){
                $return["IsError"] = TRUE;
                $return["Message"] = "Provider doku belum diaktifkan";
                goto ResultData;
            }

            if(empty($providerDoku->secret_key) || empty($providerDoku->client_id)){
                $return["IsError"] = TRUE;
                $return["Message"] = "Sekret key / client id doku belum diatur";
                goto ResultData;
            }

            $clientId = $providerDoku->client_id ?? null;
            $requestId = Str::random(128);
            $requestDate = self::dateIso8601();
            $targetPath = "/checkout/v1/payment";
            $secretKey = $providerDoku->secret_key ?? null;
            
            $digestValue = base64_encode(hash('sha256', json_encode($requestBody), true));
            $componentSignature = "Client-Id:" . $clientId . "\n" . 
                                "Request-Id:" . $requestId . "\n" .
                                "Request-Timestamp:" . $requestDate . "\n" . 
                                "Request-Target:" . $targetPath . "\n" .
                                "Digest:" . $digestValue;
            $signature = base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));
            
            $data = [
                'Client-Id' => $clientId,
                'Request-Id' => $requestId,
                'Request-Timestamp' => $requestDate,
                'Signature' => "HMACSHA256=" . $signature,
            ];

            $return["IsError"] = FALSE;
            $return["Data"] = $data;
            $return["Message"] = "Signature berhasil digenerate";
            goto ResultData;

        }catch(\Throwable $th){
            Log::emergency($th->getMessage());
            $return["IsError"] = TRUE;
            $return["Message"] = $th->getMessage();
            goto ResultData;
        }

        ResultData:
        return $return;
    }

    public static function checkoutDoku($orderId){
        $return = [];
        try{
            $order = new Order();
            $order = $order->where("id",$orderId);
            $order = $order->first();

            $ownerBank = new UserBank();
            $ownerBank = $ownerBank->whereHas("user",function($query2){
                $query2->role([RoleEnum::OWNER]);
            });
            $ownerBank = $ownerBank->where("status",UserBankEnum::STATUS_APPROVED);
            $ownerBank = $ownerBank->where("default",UserBankEnum::DEFAULT_TRUE);
            $ownerBank = $ownerBank->orderBy("created_at","DESC");
            $ownerBank = $ownerBank->first();

            $agenBank = new UserBank();
            $agenBank = $agenBank->where("status",UserBankEnum::STATUS_APPROVED);
            $agenBank = $agenBank->where("default",UserBankEnum::DEFAULT_TRUE);
            $agenBank = $agenBank->where("user_id",$order->user_id);
            $agenBank = $agenBank->orderBy("created_at","DESC");
            $agenBank = $agenBank->first();

            if(empty($ownerBank->bank_settlement_id)){
                $return["IsError"] = TRUE;
                $return["Message"] = "Settlement Bank ID Owner belum diatur";
                goto ResultData;
            }

            if(empty($agenBank->bank_settlement_id)){
                $return["IsError"] = TRUE;
                $return["Message"] = "Settlement Bank ID Agen belum diatur";
                goto ResultData;
            }

            $order->update([
                'owner_bank_settlement_id' => $ownerBank->bank_settlement_id,
                'agen_bank_settlement_id' => $agenBank->bank_settlement_id,
            ]);

            $items = [];
            foreach($order->items as $index => $row){
                $items[] = [
                    "name" => $row->product->name ?? null,
                    "qty" => $row->qty,
                    "price" => $row->product->price ?? null,
                    "sku" => $row->product->code,
                    "category" => $row->product->category->name ?? null
                ];
            }

            $requestBody = array (
                "order" => array (
                    "amount" => $order->totalNeto(),
                    "invoice_number" => $order->code,
                    "currency" => "IDR",
                    "language" => "ID",
                    "disable_retry_payment" => true,
                    "auto_redirect" => true,
                    "callback_url" => route('landing-page.orders.index',["code" => $order->code]),
                    "callback_url_cancel" => route('landing-page.orders.index',["code" => $order->code]),
                ),
                "payment" => array (
                    "payment_due_date" => ($order->type == OrderEnum::TYPE_ON_TIME_PAY) ? env("DOKU_DUE_DATE") : DateHelper::differentMinute($order->created_at,date("Y-m-d H:i:s",strtotime($order->expired_date))),
                    "payment_method_types" => [
                        "VIRTUAL_ACCOUNT_BCA",
                        "VIRTUAL_ACCOUNT_BANK_MANDIRI",
                        "VIRTUAL_ACCOUNT_BANK_SYARIAH_MANDIRI",
                        "VIRTUAL_ACCOUNT_DOKU",
                        "VIRTUAL_ACCOUNT_BRI",
                        "VIRTUAL_ACCOUNT_BNI",
                        "VIRTUAL_ACCOUNT_BANK_PERMATA",
                        "VIRTUAL_ACCOUNT_BANK_DANAMON",
                        "VIRTUAL_ACCOUNT_BANK_DANAMON",
                        "ONLINE_TO_OFFLINE_ALFA",
                        "CREDIT_CARD",
                        "EMONEY_OVO",
                        "EMONEY_SHOPEE_PAY",
                        "EMONEY_DOKU",
                    ]
                ),
                "customer" => array (
                    "id" => $order->user->code ?? null,
                    "name" => $order->user->name ?? null,
                    "phone" => $order->user->phone ?? null,
                    "email" =>  $order->user->email ?? null,
                    "country" => "ID"
                ),
                "line_items" => $items,
                "additional_info" => array (
                    "settlement" => array (
                        array (
                            "bank_account_settlement_id" => $order->owner_bank_settlement_id,
                            "value" => $order->owner_fee,
                            "type" => "PERCENTAGE"
                        ),
                        array (
                            "bank_account_settlement_id" => $order->agen_bank_settlement_id,
                            "value" => $order->agen_fee,
                            "type" => "PERCENTAGE"
                        )
                    )
                )
            );

            $credential = self::generateSignature($requestBody);

            if($credential["IsError"] == TRUE){
                $return["IsError"] = TRUE;
                $return["Message"] = $credential["Message"];
                goto ResultData;
            }
            

            $hitDoku = Http::withHeaders($credential["Data"]);
            $hitDoku = $hitDoku->post(env("DOKU_URL")."/checkout/v1/payment",$requestBody);
            
            if($hitDoku->status() == Response::HTTP_OK){

                $responseJson = $hitDoku->json();

                $orderDoku = new OrderDoku();
                $orderDoku = $orderDoku->create([
                    'order_id' => $order->id,
                    'target' => '/checkout/v1/payment',
                    'data' => $responseJson,
                ]);

                $order->update([
                    'payment_due_date' => ($order->type == OrderEnum::TYPE_ON_TIME_PAY) ? env("DOKU_DUE_DATE") : DateHelper::differentMinute($order->created_at,date("Y-m-d H:i:s",strtotime($order->expired_date))),
                    'expired_date' => $responseJson["response"]["payment"]["expired_date"],
                    'doku_token_id' => $responseJson["response"]["payment"]["token_id"],
                    'payment_url' => $responseJson["response"]["payment"]["url"],
                ]);
                

                $return["IsError"] = FALSE;
                $return["Message"] = "Checkout berhasil . Silahkan lakukan pembayaran hingga waktu yang ditentukan";
                goto ResultData;
            }
            else{
                Log::emergency($hitDoku->json());
                $return["IsError"] = TRUE;
                $return["Message"] = "Terjadi kesalahan saat memproses data";
                goto ResultData;
            }
        }catch(\Throwable $th){
            Log::emergency($th->getMessage());
            $return["IsError"] = TRUE;
            $return["Message"] = $th->getMessage();
            goto ResultData;
        }

        ResultData:
        return $return;
    }

    private static function dateIso8601(){
        date_default_timezone_set('UTC');
        $timestamp = (new DateTime())->format('Y-m-d\TH:i:s\Z');

        return $timestamp;
    }
}
