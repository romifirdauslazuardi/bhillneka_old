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

    public function doku(Request $request)
    {
        DB::beginTransaction();
        try {
            $providerDoku = $this->provider;
            $providerDoku = $providerDoku->where("status", ProviderEnum::STATUS_TRUE);
            $providerDoku = $providerDoku->where("type", ProviderEnum::TYPE_DOKU);
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

            $decode = json_decode($notificationBody, true);

            if ($finalSignature == $notificationHeader['Signature']) {
                $findOrder = $this->order;
                $findOrder = $findOrder->where("code", $decode["order"]["invoice_number"]);
                $findOrder = $findOrder->first();

                $findOrder->update([
                    'status' => $decode["transaction"]["status"],
                    'doku_service_id' => $decode["service"]["id"],
                    'doku_acquirer_id' => $decode["acquirer"]["id"],
                    'doku_channel_id' => $decode["channel"]["id"],
                ]);

                if ($decode["transaction"]["status"] == OrderEnum::STATUS_SUCCESS) {

                    $findOrder->update([
                        'paid_date' => date("Y-m-d H:i:s"),
                    ]);

                    if ($findOrder->business->category->name == BusinessCategoryEnum::MIKROTIK) {

                        foreach ($findOrder->items as $index => $row) {
                            $mikrotikConfig = SettingHelper::mikrotikConfig($row->product->mikrotik_config_id ?? null);
                            $ip = $mikrotikConfig->ip ?? null;
                            $username = $mikrotikConfig->username ?? null;
                            $password = $mikrotikConfig->password ?? null;
                            $port = $mikrotikConfig->port ?? null;

                            $connect = $this->routerosApi;
                            $connect->debug("false");

                            if (!$connect->connect($ip, $username, $password, $port)) {
                                Log::emergency('Callback : Koneksi dengan mikrotik gagal. Silahkan cek konfigurasi anda');
                            }

                            if (!empty($row->order_mikrotik)) {
                                if ($row->order_mikrotik->type == OrderMikrotikEnum::TYPE_PPPOE) {
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

                                    if (!empty($row->order_mikrotik->mikrotik_id)) {
                                        $connectData = array_merge($connectData, [".id" => $row->order_mikrotik->mikrotik_id]);
                                        $connect = $connect->comm('/ppp/secret/set', $connectData);
                                    } else {
                                        $connect = $connect->comm('/ppp/secret/add', $connectData);
                                    }

                                    $connectLog = LogHelper::mikrotikLog($connect);

                                    if ($connectLog["IsError"] == TRUE) {
                                        Log::emergency("Callback : " . $connectLog["Message"]);
                                    }

                                    $row->order_mikrotik()->update([
                                        'disabled' => OrderMikrotikEnum::DISABED_NO,
                                    ]);

                                    if (empty($row->order_mikrotik->mikrotik_id)) {
                                        if ($connectLog["IsError"] == FALSE) {
                                            $row->order_mikrotik()->update([
                                                'mikrotik_id' => $connect
                                            ]);
                                        }
                                    }
                                } else {
                                    $connectData = [
                                        'name' => $row->order_mikrotik->username ?? null,
                                        'password' => $row->order_mikrotik->password ?? null,
                                        'server' => $row->order_mikrotik->server ?? null,
                                        'profile' => $row->order_mikrotik->profile ?? null,
                                        'limit-uptime' => $row->order_mikrotik->time_limit ?? null,
                                        'comment' => $row->order_mikrotik->comment ?? null,
                                        'disabled' => OrderMikrotikEnum::DISABED_NO,
                                    ];

                                    if (!empty($row->order_mikrotik->address)) {
                                        $connectData = array_merge($connectData, ["address" => $row->order_mikrotik->address]);
                                    }

                                    if (!empty($row->order_mikrotik->mac_address)) {
                                        $connectData = array_merge($connectData, ["mac-address" => $row->order_mikrotik->mac_address]);
                                    }

                                    if (!empty($row->order_mikrotik->mikrotik_id)) {
                                        $connectData = array_merge($connectData, [".id" => $row->order_mikrotik->mikrotik_id]);
                                        $connect = $connect->comm('/ip/hotspot/user/set', $connectData);
                                    } else {
                                        $connect = $connect->comm('/ip/hotspot/user/add', $connectData);
                                    }

                                    $connectLog = LogHelper::mikrotikLog($connect);

                                    if ($connectLog["IsError"] == TRUE) {
                                        Log::emergency("Callback : " . $connectLog["Message"]);
                                    }

                                    $row->order_mikrotik()->update([
                                        'disabled' => OrderMikrotikEnum::DISABED_NO,
                                    ]);

                                    if (empty($row->order_mikrotik->mikrotik_id)) {
                                        if ($connectLog["IsError"] == FALSE) {
                                            $row->order_mikrotik()->update([
                                                'mikrotik_id' => $connect
                                            ]);
                                        }
                                    }

                                    $explodeTimeLimit = str_split($row->order_mikrotik->time_limit);

                                    $totalSecond = 0;
                                    foreach ($explodeTimeLimit as $i => $value) {
                                        if (isset($explodeTimeLimit[$i + 1])) {
                                            if (strtolower($explodeTimeLimit[$i + 1]) == "d") {
                                                $totalSecond += (((int)$value) * 24 * 60 * 60);
                                            }
                                            if (strtolower($explodeTimeLimit[$i + 1]) == "h") {
                                                $totalSecond += (((int)$value) * 60 * 60);
                                            }
                                            if (strtolower($explodeTimeLimit[$i + 1]) == "m") {
                                                $totalSecond += (((int)$value) * 60);
                                            }
                                            if (strtolower($explodeTimeLimit[$i + 1]) == "s") {
                                                $totalSecond += (((int)$value));
                                            }
                                        }
                                    }

                                    OrderMikrotikHotspotJob::dispatch($findOrder->id)->delay(now()->addSeconds($totalSecond));
                                }
                            }
                        }
                    }

                    $settingFee = SettingHelper::checkSettingFee($findOrder->id);

                    if ($settingFee["IsError"] == TRUE) {
                        Log::emergency("CallbackService : " . $settingFee["Message"]);
                    } else {
                        $settingFee = $settingFee["Data"];

                        $findOrder->update([
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
                }

                $orderDoku = $this->orderDoku;
                $orderDoku = $orderDoku->create([
                    'order_id' => $findOrder->id,
                    'target' => '/api/payments/notifications',
                    'data' => $decode
                ]);

                $received = $this->user;
                $received = $received->where(function ($query2) use ($findOrder) {
                    $query2->role([RoleEnum::OWNER]);
                    $query2->orWhere('id', $findOrder->user_id);
                    $query2->orWhere('id', $findOrder->author_id);
                });
                $received = $received->get();

                Notification::send($received, new PaymentNotification(route('dashboard.orders.show', $findOrder->id), 'Pembayaran Pesanan', 'Pembayaran dengan kode transaksi ' . $findOrder->code . " sebesar <b>" . number_format($findOrder->totalNeto(), 0, ',', '.') . "</b> telah <b>[" . $decode["transaction"]["status"] . "</b>] dilakukan.", $findOrder));

                WhatsappHelper::sendWhatsappOrderTemplate($findOrder->id);

                DB::commit();

                return $this->response(true, "Callback berhasil", null, Response::HTTP_OK);
            } else {

                DB::rollBack();

                return $this->response(false, "Invalid Signature", null, Response::HTTP_BAD_REQUEST);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::emergency($th->getMessage());
            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
