<?php

namespace App\Services;

use App\Enums\BusinessCategoryEnum;
use App\Enums\DokuEnum;
use App\Enums\OrderEnum;
use App\Enums\OrderMikrotikEnum;
use App\Enums\ProductEnum;
use App\Enums\ProductStockEnum;
use App\Enums\ProviderEnum;
use App\Enums\RoleEnum;
use App\Helpers\CodeHelper;
use App\Helpers\DateHelper;
use App\Helpers\LogHelper;
use App\Helpers\PaymentHelper;
use App\Helpers\SettingHelper;
use App\Helpers\UploadHelper;
use App\Helpers\WhatsappHelper;
use App\Http\Requests\Order\ProofOrderRequest;
use App\Services\BaseService;
use App\Http\Requests\Order\StoreRequest;
use App\Http\Requests\Order\UpdateProgressRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Http\Requests\Order\UpdateStatusRequest;
use App\Http\Requests\Order\UpdateProviderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderDoku;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\SettingFee;
use App\Models\User;
use App\Models\RouterosAPI;
use App\Models\OrderMikrotik;
use App\Notifications\OrderNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Jobs\OrderExpiredJob;
use App\Models\Business;
use Auth;
use DB;
use Log;
use Throwable;
use Notification;

class OrderService extends BaseService
{
    protected $order;
    protected $orderItem;
    protected $orderDoku;
    protected $product;
    protected $productStock;
    protected $settingFee;
    protected $user;
    protected $routerosApi;
    protected $orderMikrotik;
    protected $business;

    public function __construct()
    {
        $this->order = new Order();
        $this->orderItem = new OrderItem();
        $this->orderDoku = new OrderDoku;
        $this->product = new Product();
        $this->settingFee = new SettingFee();
        $this->user = new User();
        $this->productStock = new ProductStock();
        $this->routerosApi = new RouterosAPI();
        $this->orderMikrotik = new OrderMikrotik();
        $this->business = new Business();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));
        $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
        $customer_id = (empty($request->customer_id)) ? null : trim(strip_tags($request->customer_id));
        $status = (empty($request->status)) ? null : trim(strip_tags($request->status));
        $from_date = (empty($request->from_date)) ? null : trim(strip_tags($request->from_date));
        $to_date = (empty($request->to_date)) ? null : trim(strip_tags($request->to_date));
        $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));
        $category_id = (empty($request->category_id)) ? null : trim(strip_tags($request->category_id));
        $progress = (!isset($request->progress)) ? null : trim(strip_tags($request->progress));

        if(Auth::user()->hasRole([RoleEnum::AGEN])){
            $user_id = Auth::user()->id;
        }
        if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
            $user_id = Auth::user()->user_id;
        }
        if(Auth::user()->hasRole([RoleEnum::CUSTOMER])){
            $customer_id = Auth::user()->id;
        }
        if(!empty(Auth::user()->business_id)){
            $business_id = Auth::user()->business_id;
        }

        $table = $this->order;
        if (!empty($search)) {
            $table = $this->order->where(function ($query2) use ($search) {
                $query2->where('code', 'like', '%' . $search . '%');
                $query2->orWhere('customer_name', 'like', '%' . $search . '%');
                $query2->orWhere('customer_phone', 'like', '%' . $search . '%');
            });
        }
        if(!empty($user_id)){
            $table = $table->where("user_id",$user_id);
        }
        if(!empty($customer_id)){
            $table = $table->where("customer_id",$customer_id);
        }
        if(!empty($status)){
            $table = $table->where("status",$status);
        }
        if(!empty($business_id)){
            $table = $table->where("business_id",$business_id);
        }
        if(!empty($category_id)){
            $table = $table->where("category_id",$category_id);
        }
        if(!empty($progress)){
            $table = $table->where("progress",$progress);
        }
        if(!empty($from_date)){
            $table = $table->whereDate("created_at",">=",$from_date);
        }
        if(!empty($to_date)){
            $table = $table->whereDate("created_at","<=",$to_date);
        }
        
        $table = $table->orderBy('created_at', 'DESC');

        if ($paginate) {
            $table = $table->paginate(10);
            $table = $table->withQueryString();
        } else {
            $table = $table->get();
        }

        return $this->response(true, 'Berhasil mendapatkan data', $table);
    }

    public function show($id)
    {
        try {
            $result = $this->order;
            if(Auth::user()->hasRole([RoleEnum::AGEN])){
                $result = $result->where("user_id",Auth::user()->id);
            }
            if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
                $result = $result->where("user_id",Auth::user()->user_id);
            }
            if(Auth::user()->hasRole([RoleEnum::CUSTOMER])){
                $result = $result->where("customer_id",Auth::user()->id);
            }
            if(!empty(Auth::user()->business_id)){
                $result = $result->where("business_id",Auth::user()->business_id);
            }
            $result = $result->where('id',$id);
            $result = $result->first();

            if(!$result){
                return $this->response(false, "Data tidak ditemukan");
            }

            $related = $this->order;
            $related = $related->where("user_id",$result->user_id);
            $related = $related->where("id","!=",$result->id);
            if(!empty(Auth::user()->business_id)){
                $related = $related->where("business_id",Auth::user()->business_id);
            }
            $related = $related->orderBy("created_at","DESC");
            $related = $related->get();

            $result->related = $related;


            $orderDueDate = $this->order;
            $orderDueDate = $orderDueDate->where("order_id",$result->id);
            $orderDueDate = $orderDueDate->orderBy("created_at","DESC");
            $orderDueDate = $orderDueDate->get();

            $result->related = $related;
            $result->orderDueDate = $orderDueDate;

            return $this->response(true, 'Berhasil mendapatkan data', $result);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function showByCode($code)
    {
        try {
            $result = $this->order;
            $result = $result->where('code',$code);
            $result = $result->first();

            if(!$result){
                return $this->response(false, "Data tidak ditemukan");
            }

            return $this->response(true, 'Berhasil mendapatkan data', $result);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
            $customer_id = (empty($request->customer_id)) ? null : trim(strip_tags($request->customer_id));
            $customer_name = (empty($request->customer_name)) ? null : trim(strip_tags($request->customer_name));
            $customer_phone = (empty($request->customer_phone)) ? null : trim(strip_tags($request->customer_phone));
            $customer_email = (empty($request->customer_email)) ? null : trim(strip_tags($request->customer_email));
            $provider_id = (empty($request->provider_id)) ? null : trim(strip_tags($request->provider_id));
            $note = (empty($request->note)) ? null : trim(strip_tags($request->note));
            $repeater = $request->repeater;
            $discount = (empty($request->discount)) ? $request->discount : 0;
            $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));
            $type = (empty($request->type)) ? null : trim(strip_tags($request->type));
            $fnb_type = (!isset($request->fnb_type)) ? null : trim(strip_tags($request->fnb_type));
            $table_id = (empty($request->table_id)) ? null : trim(strip_tags($request->table_id));
            $repeat_order_at = (empty($request->repeat_order_at)) ? null : trim(strip_tags($request->repeat_order_at));
            $author_id = Auth::user()->id ?? null;
            $repeat_order_status = OrderEnum::REPEAT_ORDER_STATUS_FALSE;

            $discount = str_replace(".","",$discount);
            $discount = (int)$discount;

            $business = $this->business;
            $business = $business->where("id",$business_id);
            $business = $business->firstOrFail();

            if($business->category->name == BusinessCategoryEnum::MIKROTIK){
                $mikrotikConfig = SettingHelper::mikrotikConfig();

                if(empty($mikrotikConfig)){
                    DB::rollBack();
                    return $this->response(false, "Koneksi dengan mikrotik gagal. Silahkan cek konfigurasi anda");
                }
            }

            if($type == OrderEnum::TYPE_DUE_DATE){
                $repeat_order_status = OrderEnum::REPEAT_ORDER_STATUS_TRUE;
            }

            if(request()->routeIs("landing-page.buy-products.store")){
                $addItemOrder = self::addItemOrderPayLater($request);

                if($addItemOrder["IsError"] == TRUE){
                    DB::rollBack();
                    return $this->response(false, $addItemOrder["Message"]);
                }
                else{
                    if(!empty($addItemOrder["Data"])){
                        DB::commit();
                        return $this->response(true, $addItemOrder["Message"],$addItemOrder["Data"]);
                    }
                }
            }

            $order = $this->order->create([
                'code' => CodeHelper::generateOrder(),
                'user_id' => $user_id,
                'customer_id' => $customer_id,
                'customer_name' => $customer_name,
                'customer_phone' => $customer_phone,
                'customer_email' => $customer_email,
                'provider_id' => $provider_id,
                'note' => $note,
                'discount' => (!empty($discount)) ? $discount : 0,
                'status' => OrderEnum::STATUS_WAITING_PAYMENT,
                'progress' => OrderEnum::PROGRESS_DRAFT,
                'author_id' => $author_id,
                'expired_date' => date("YmdHis",strtotime(date("Y-m-d H:i:s")." + ".(env("DOKU_DUE_DATE")+1)."minutes")),
                'business_id' => $business_id,
                'type' => $type,
                'fnb_type' => $fnb_type,
                'table_id' => $table_id,
                'repeat_order_at' => $repeat_order_at,
                'repeat_order_status' => $repeat_order_status,
            ]);

            if($order->provider->type == ProviderEnum::TYPE_MANUAL_TRANSFER){
                $order->update([
                    "doku_service_id" => DokuEnum::SERVICE_MANUAL_PAYMENT,
                ]);
            }

            $total = 0;
            foreach($repeater as $index => $row){
                $product_id = $row["product_id"] ?? null;
                $qty = $row["qty"] ?? null;
                $disc = $row["discount"] ?? 0;
                $username = $row["username"] ?? null;
                $password = $row["password"] ?? null;
                $service = $row["service"] ?? null;
                $server = $row["server"] ?? null;
                $profile = $row["profile"] ?? null;
                $local_address = $row["local_address"] ?? null;
                $remote_address = $row["remote_address"] ?? null;
                $comment = $row["comment"] ?? null;
                $time_limit = $row["time_limit"] ?? null;
                $auto_userpassword = $row["auto_userpassword"] ?? null;
                $address = $row["address"] ?? null;
                $mac_address = $row["mac_address"] ?? null;
                $expired_month = $row["expired_month"] ?? null;
                $expired_date = null;

                if(empty($product_id)){
                    DB::rollBack();
                    return $this->response(false, "Produk ID tidak boleh kosong");
                }

                if(empty($qty)){
                    DB::rollBack();
                    return $this->response(false, "Qty produk tidak boleh kosong");
                }

                if($qty <= 0){
                    DB::rollBack();
                    return $this->response(false, "Qty produk tidak boleh kosong");
                }

                if($type == OrderEnum::TYPE_DUE_DATE){
                    $expired_date = null;
                    $expired_month = null;
                }

                $productResult = $this->product;
                $productResult = $productResult->where("id",$product_id);
                $productResult = $productResult->first();

                if(!$productResult){
                    DB::rollBack();
                    return $this->response(false, "Produk tidak ditemukan");
                }

                if($productResult->price <= 0){
                    DB::rollBack();
                    return $this->response(false, "Harga produk ".$productResult->name." belum diatur");
                }

                if($business->category->name == BusinessCategoryEnum::MIKROTIK){

                    if(empty($profile)){
                        DB::rollBack();
                        return $this->response(false, "Profile tidak boleh kosong");
                    }

                    if($productResult->mikrotik == ProductEnum::MIKROTIK_PPPOE){
                        if(empty($service)){
                            DB::rollBack();
                            return $this->response(false, "Service tidak boleh kosong");
                        }
                        if(empty($local_address)){
                            DB::rollBack();
                            return $this->response(false, "Local address tidak boleh kosong");
                        }
                        if(empty($remote_address)){
                            DB::rollBack();
                            return $this->response(false, "Remote address tidak boleh kosong");
                        }
                    }
                    else{
                        if(empty($server)){
                            DB::rollBack();
                            return $this->response(false, "Server tidak boleh kosong");
                        }

                        if(!isset($time_limit)){
                            DB::rollBack();
                            return $this->response(false, "Time limit tidak boleh kosong");
                        }

                        if(!isset($auto_userpassword)){
                            DB::rollBack();
                            return $this->response(false, "Jenis pengisian tidak boleh kosong");
                        }

                        if($auto_userpassword == OrderMikrotikEnum::AUTO_USERPASSWORD_TRUE){
                            $username = Str::random(6);
                            $password = $username;
                        }
                    }

                    if(empty($username)){
                        DB::rollBack();
                        return $this->response(false, "Username tidak boleh kosong");
                    }
                    
                    if(empty($password)){
                        DB::rollBack();
                        return $this->response(false, "Password tidak boleh kosong");
                    }
                }

                if($productResult->is_using_stock == ProductEnum::IS_USING_STOCK_TRUE){
                    $stockReady = $productResult->stocks()->where("type",ProductStockEnum::TYPE_MASUK)->sum("qty") - $productResult->stocks()->where("type",ProductStockEnum::TYPE_KELUAR)->sum("qty");

                    if($stockReady <= 0){
                        DB::rollBack();
                        return $this->response(false, "Stok produk ".$productResult->name." belum diatur");
                    }

                    if($stockReady < $qty){
                        DB::rollBack();
                        return $this->response(false, "Stok produk ".$productResult->name." tinggal ".$stockReady);
                    }
                }
                
                $disc = str_replace(".","",$disc);

                $dataOrderItem = [
                    'order_id' => $order->id,
                    'product_id' => $productResult->id,
                    'product_code' => $productResult->code,
                    'product_name' => $productResult->name,
                    'product_price' => $productResult->price,
                    'qty' => $qty,
                    'discount' => $disc,
                    'author_id' => $author_id
                ];

                $orderItem = $this->orderItem;
                $orderItem = $orderItem->where("order_id",$order->id);
                $orderItem = $orderItem->where("product_id",$productResult->id);
                $orderItem = $orderItem->first();

                $oldQty = $orderItem->qty ?? null;

                if($orderItem){
                    $orderItem->update($dataOrderItem);

                    if($productResult->is_using_stock == ProductEnum::IS_USING_STOCK_TRUE){

                        if($oldQty != $orderItem->qty){

                            $available = $orderItem->product->stocks()->where("type",ProductStockEnum::TYPE_MASUK)->sum("qty") - $orderItem->product->stocks()->where("type",ProductStockEnum::TYPE_KELUAR)->sum("qty");

                            $this->productStock->create([
                                'date' => date("Y-m-d"),
                                'type' => ProductStockEnum::TYPE_MASUK,
                                'product_id' => $productResult->id,
                                'qty' => $oldQty,
                                'available' => $available + $oldQty,
                                'note' => 'Stok masuk order #'.$order->code,
                                'author_id' => $author_id,
                            ]);
    
                            $this->productStock->create([
                                'date' => date("Y-m-d"),
                                'type' => ProductStockEnum::TYPE_KELUAR,
                                'product_id' => $productResult->id,
                                'qty' => $orderItem->qty,
                                'available' => $available - $orderItem->qty,
                                'note' => 'Stok keluar order #'.$order->code,
                                'author_id' => $author_id,
                            ]);
                        }
                    }
                }
                else{
                    $orderItem = $this->orderItem->create($dataOrderItem);

                    if($productResult->is_using_stock == ProductEnum::IS_USING_STOCK_TRUE){
                        $available = $orderItem->product->stocks()->where("type",ProductStockEnum::TYPE_MASUK)->sum("qty") - $orderItem->product->stocks()->where("type",ProductStockEnum::TYPE_KELUAR)->sum("qty");

                        $this->productStock->create([
                            'date' => date("Y-m-d"),
                            'type' => ProductStockEnum::TYPE_KELUAR,
                            'product_id' => $productResult->id,
                            'qty' => $qty,
                            'available' => $available - $qty,
                            'note' => 'Stok keluar order #'.$order->code,
                            'author_id' => $author_id,
                        ]);
                    }
                }

                $total += ( $qty * $productResult->price ) - $disc;
                
                if($business->category->name == BusinessCategoryEnum::MIKROTIK){

                    if(!empty($expired_month)){
                        $expired_date = date("Y-m-d",strtotime(date("Y-m-d")." + ".$expired_month." month"));
                    }

                    $dataOrderMikrotik = [
                        'order_item_id' => $orderItem->id,
                        'username' => $username,
                        'profile' => $profile,
                        'service' => $service,
                        'server' => $server,
                        'address' => $address,
                        'mac_address' => $mac_address,
                        'password' => $password,
                        'type' => $productResult->mikrotik,
                        'expired_date' => $expired_date,
                        'expired_month' => $expired_month,
                        'time_limit' => $time_limit,
                        'comment' => $comment,
                        'local_address' => $local_address,
                        'remote_address' => $remote_address,
                        'disabled' => OrderMikrotikEnum::DISABLED_TRUE,
                        'author_id' => Auth::user()->id,
                    ];

                    if($productResult->mikrotik == ProductEnum::MIKROTIK_PPPOE){
                        $dataOrderMikrotik = array_merge($dataOrderMikrotik,["type" => OrderMikrotikEnum::TYPE_PPPOE]);
                    }
                    else{
                        $dataOrderMikrotik = array_merge($dataOrderMikrotik,["type" => OrderMikrotikEnum::TYPE_HOTSPOT]);
                    }

                    $this->orderMikrotik->updateOrCreate([
                        'order_item_id' => $orderItem->id,
                    ],$dataOrderMikrotik);

                    $mikrotikConfig = SettingHelper::mikrotikConfig($order->business_id,$order->business->user_id);
                    $ip = $mikrotikConfig->ip ?? null;
                    $username = $mikrotikConfig->username ?? null;
                    $password = $mikrotikConfig->password ?? null;
                    $port = $mikrotikConfig->port ?? null;
                    
                    $connect = $this->routerosApi;
                    $connect->debug("false");

                    if(!$connect->connect($ip,$username,$password,$port)){
                        return $this->response(false,'Koneksi dengan mikrotik gagal. Silahkan cek konfigurasi anda');
                    }

                    if($dataOrderMikrotik["type"] == OrderMikrotikEnum::TYPE_PPPOE){
                        $connect = $connect->comm('/ppp/secret/print');
                    }
                    else{
                        $connect = $connect->comm('/ip/hotspot/user/print');
                    }

                    $connectLog = LogHelper::mikrotikLog($connect);

                    if($connectLog["IsError"] == TRUE){
                        return $this->response(false, $connectLog["Message"]);
                    }

                    foreach($connect as $i => $v){
                        if($v["name"] == $dataOrderMikrotik["username"]){
                            return $this->response(false, "Username ". $dataOrderMikrotik["username"]. " sudah terdaftar dimikrotik");
                        }
                    }
                }
            }
            
            $total -= $discount;

            if($total <= 0){
                DB::rollBack();
                return $this->response(false, "Total transaksi tidak boleh kurang dari samadengan 0");
            }

            $settingFee = SettingHelper::checkSettingFee($order->id);

            if($settingFee["IsError"] == TRUE){
                DB::rollBack();
                return $this->response(false, $settingFee["Message"]);
            }
            else{
                $settingFee = $settingFee["Data"];
            }

            $order->update([
                'owner_fee' => $settingFee["owner_fee"],
                'agen_fee' => $settingFee["agen_fee"],
                'total_owner_fee' => $settingFee["total_owner_fee"],
                'total_agen_fee' => $settingFee["total_agen_fee"],
                'customer_type_fee' => $settingFee["customer_type_fee"],
                'customer_value_fee' => $settingFee["customer_value_fee"],
                'customer_total_fee' => $settingFee["customer_total_fee"],
            ]);

            if($order->provider->type == ProviderEnum::TYPE_PAY_LATER){
                if(empty($business->user_pay_later->status)){
                    DB::rollBack();
                    return $this->response(false, "Pengaturan bayar nanti bisnis ".$business->name." tidak aktif");
                }

                $order->update([
                    'status' => OrderEnum::STATUS_PAY_LATER
                ]);
            }

            if($order->provider->type == ProviderEnum::TYPE_DOKU){
                $checkoutDoku = PaymentHelper::checkoutDoku($order->id);

                if($checkoutDoku["IsError"] == TRUE){
                    DB::rollBack();
                    return $this->response(false, $checkoutDoku["Message"]);
                }
            }

            if($order->provider->type != ProviderEnum::TYPE_PAY_LATER){
                
                OrderExpiredJob::dispatch($order->id)->delay(now()->addMinutes((env("DOKU_DUE_DATE")+1)));

                WhatsappHelper::sendWhatsappOrderTemplate($order->id,"pesanan");
            }

            DB::commit();

            return $this->response(true, 'Checkout berhasil dilakukan. Silahkan lakukan pembayaran sampai batas waktu yang telah ditentukan',$order);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function update(UpdateRequest $request,$id)
    {
        DB::beginTransaction();
        try {
            $customer_id = (empty($request->customer_id)) ? null : trim(strip_tags($request->customer_id));
            $customer_name = (empty($request->customer_name)) ? null : trim(strip_tags($request->customer_name));
            $customer_phone = (empty($request->customer_phone)) ? null : trim(strip_tags($request->customer_phone));
            $customer_email = (empty($request->customer_email)) ? null : trim(strip_tags($request->customer_email));
            $provider_id = (empty($request->provider_id)) ? null : trim(strip_tags($request->provider_id));
            $note = (empty($request->note)) ? null : trim(strip_tags($request->note));
            $repeater = $request->repeater;
            $discount = (empty($request->discount)) ? $request->discount : 0;
            $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));
            $type = (empty($request->type)) ? null : trim(strip_tags($request->type));
            $fnb_type = (!isset($request->fnb_type)) ? null : trim(strip_tags($request->fnb_type));
            $table_id = (empty($request->table_id)) ? null : trim(strip_tags($request->table_id));
            $status = (empty($request->status)) ? null : trim(strip_tags($request->status));
            $progress = (empty($request->progress)) ? null : trim(strip_tags($request->progress));
            $repeat_order_at = (empty($request->repeat_order_at)) ? null : trim(strip_tags($request->repeat_order_at));
            $repeat_order_status = (!isset($request->repeat_order_status)) ? OrderEnum::REPEAT_ORDER_STATUS_FALSE : trim(strip_tags($request->repeat_order_status));
            $author_id = Auth::user()->id ?? null;
            
            $result = $this->order->findOrFail($id);

            $oldStatus = $result->status;

            $discount = str_replace(".","",$discount);
            $discount = (int)$discount;

            $business = $this->business;
            $business = $business->where("id",$business_id);
            $business = $business->firstOrFail();

            $result->update([
                'customer_id' => $customer_id,
                'customer_name' => $customer_name,
                'customer_phone' => $customer_phone,
                'customer_email' => $customer_email,
                'provider_id' => $provider_id,
                'note' => $note,
                'discount' => (!empty($discount)) ? $discount : 0,
                'status' => $status,
                'progress' => $progress,
                'author_id' => $author_id,
                'business_id' => $business_id,
                'type' => $type,
                'fnb_type' => $fnb_type,
                'table_id' => $table_id,
                'repeat_order_at' => $repeat_order_at,
                'repeat_order_status' => $repeat_order_status,
            ]);

            if($oldStatus != OrderEnum::STATUS_SUCCESS){
                if($status == OrderEnum::STATUS_SUCCESS){
                    $result->update([
                        'paid_at' => date("Y-m-d H:i:s")
                    ]);
                }
            }

            $productIdNotDelete = [];
            $total = 0;
            foreach($repeater as $index => $row){
                $mikrotik_id = $row["mikrotik_id"] ?? null;
                $product_id = $row["product_id"] ?? null;
                $qty = $row["qty"] ?? null;
                $disc = $row["discount"] ?? 0;
                $username = $row["username"] ?? null;
                $password = $row["password"] ?? null;
                $service = $row["service"] ?? null;
                $server = $row["server"] ?? null;
                $profile = $row["profile"] ?? null;
                $local_address = $row["local_address"] ?? null;
                $remote_address = $row["remote_address"] ?? null;
                $comment = $row["comment"] ?? null;
                $time_limit = $row["time_limit"] ?? null;
                $auto_userpassword = $row["auto_userpassword"] ?? null;
                $expired_month = $row["expired_month"] ?? null;
                $expired_date = null;

                $stockReady = 0;

                if(empty($product_id)){
                    DB::rollBack();
                    return $this->response(false, "Produk ID tidak boleh kosong");
                }

                if(empty($qty)){
                    DB::rollBack();
                    return $this->response(false, "Qty produk tidak boleh kosong");
                }

                if($qty <= 0){
                    DB::rollBack();
                    return $this->response(false, "Qty produk tidak boleh kosong");
                }

                if($type == OrderEnum::TYPE_DUE_DATE){
                    $expired_date = null;
                    $expired_month = null;
                }

                $productResult = $this->product;
                $productResult = $productResult->where("id",$product_id);
                $productResult = $productResult->first();

                if(!$productResult){
                    DB::rollBack();
                    return $this->response(false, "Produk tidak ditemukan");
                }

                if($productResult->price <= 0){
                    DB::rollBack();
                    return $this->response(false, "Harga produk ".$productResult->name." belum diatur");
                }

                if($business->category->name == BusinessCategoryEnum::MIKROTIK){
                    if(empty($profile)){
                        DB::rollBack();
                        return $this->response(false, "Profile tidak boleh kosong");
                    }
                    
                    if($productResult->mikrotik == ProductEnum::MIKROTIK_PPPOE){
                        if(empty($service)){
                            DB::rollBack();
                            return $this->response(false, "Service tidak boleh kosong");
                        }
                        if(empty($local_address)){
                            DB::rollBack();
                            return $this->response(false, "Local address tidak boleh kosong");
                        }
                        if(empty($remote_address)){
                            DB::rollBack();
                            return $this->response(false, "Remote address tidak boleh kosong");
                        }
                    }
                    else{
                        if(!isset($auto_userpassword)){
                            DB::rollBack();
                            return $this->response(false, "Jenis pengisian tidak boleh kosong");
                        }

                        if(empty($server)){
                            DB::rollBack();
                            return $this->response(false, "Server tidak boleh kosong");
                        }

                        if(!isset($time_limit)){
                            DB::rollBack();
                            return $this->response(false, "Time limit tidak boleh kosong");
                        }
                        
                        if(empty($mikrotik_id)){
                            if($auto_userpassword == OrderMikrotikEnum::AUTO_USERPASSWORD_TRUE){
                                if(empty($username) && empty($password)){
                                    $username = Str::random(6);
                                    $password = $username;
                                }
                            }
                        }
                    }

                    if(empty($username)){
                        DB::rollBack();
                        return $this->response(false, "Username tidak boleh kosong");
                    }
                    
                    if(empty($password)){
                        DB::rollBack();
                        return $this->response(false, "Password tidak boleh kosong");
                    }
                }

                if($productResult->is_using_stock == ProductEnum::IS_USING_STOCK_TRUE){
                    $stockReady = $productResult->stocks()->sum("qty");

                    if($stockReady <= 0){
                        DB::rollBack();
                        return $this->response(false, "Stok produk belum diatur");
                    }
                }

                $disc = str_replace(".","",$disc);
                $disc = (int)$disc;

                $dataOrderItem = [
                    'auto_userpassword' => $auto_userpassword,
                    'order_id' => $id,
                    'product_id' => $productResult->id,
                    'product_code' => $productResult->code,
                    'product_name' => $productResult->name,
                    'product_price' => $productResult->price,
                    'qty' => $qty,
                    'discount' => $disc,
                    'author_id' => $author_id
                ];

                $orderItem = $this->orderItem;
                $orderItem = $orderItem->where("order_id",$id);
                $orderItem = $orderItem->where("product_id",$productResult->id);
                $orderItem = $orderItem->first();

                $oldQty = $orderItem->qty ?? null;

                if($orderItem){
                    $orderItem->update($dataOrderItem);

                    if($productResult->is_using_stock == ProductEnum::IS_USING_STOCK_TRUE){

                        if(($stockReady+$oldQty) < $qty){
                            DB::rollBack();
                            return $this->response(false, "Stok produk ".$productResult->name." tinggal ".$stockReady);
                        }

                        if($oldQty != $orderItem->qty){
                            $available = $orderItem->product->stocks()->where("type",ProductStockEnum::TYPE_MASUK)->sum("qty") - $orderItem->product->stocks()->where("type",ProductStockEnum::TYPE_KELUAR)->sum("qty");

                            $this->productStock->create([
                                'date' => date("Y-m-d"),
                                'type' => ProductStockEnum::TYPE_MASUK,
                                'product_id' => $productResult->id,
                                'qty' => $oldQty,
                                'available' => $available + $oldQty,
                                'note' => 'Stok masuk order #'.$result->code,
                                'author_id' => $author_id,
                            ]);
    
                            $this->productStock->create([
                                'date' => date("Y-m-d"),
                                'type' => ProductStockEnum::TYPE_KELUAR,
                                'product_id' => $productResult->id,
                                'qty' => $orderItem->qty,
                                'available' => $available - $orderItem->qty,
                                'note' => 'Stok keluar order #'.$result->code,
                                'author_id' => $author_id,
                            ]);
                        }
                    }
                }
                else{
                    $orderItem = $this->orderItem->create($dataOrderItem);

                    if($productResult->is_using_stock == ProductEnum::IS_USING_STOCK_TRUE){
                        $available = $orderItem->product->stocks()->where("type",ProductStockEnum::TYPE_MASUK)->sum("qty") - $orderItem->product->stocks()->where("type",ProductStockEnum::TYPE_KELUAR)->sum("qty");

                        $this->productStock->create([
                            'date' => date("Y-m-d"),
                            'type' => ProductStockEnum::TYPE_KELUAR,
                            'product_id' => $productResult->id,
                            'qty' => $qty,
                            'available' => $available - $qty,
                            'note' => 'Stok keluar order #'.$result->code,
                            'author_id' => $author_id,
                        ]);
                    }
                }

                $productIdNotDelete[] = $productResult->id;
                
                $total += ( $qty * $productResult->price ) - $disc; 
                
                if($business->category->name == BusinessCategoryEnum::MIKROTIK){

                    if(!empty($expired_month)){
                        $expired_date = date("Y-m-d",strtotime(date("Y-m-d",strtotime($result->created_at))." + ".$expired_month." month"));
                    }

                    $dataOrderMikrotik = [
                        'order_item_id' => $orderItem->id,
                        'username' => $username,
                        'profile' => $profile,
                        'service' => $service,
                        'server' => $server,
                        'password' => $password,
                        'type' => $productResult->mikrotik,
                        'expired_date' => $expired_date,
                        'expired_month' => $expired_month,
                        'time_limit' => $time_limit,
                        'comment' => $comment,
                        'local_address' => $local_address,
                        'remote_address' => $remote_address,
                        'author_id' => $author_id,
                    ];

                    if($productResult->mikrotik == ProductEnum::MIKROTIK_PPPOE){
                        $dataOrderMikrotik = array_merge($dataOrderMikrotik,["type" => OrderMikrotikEnum::TYPE_PPPOE]);
                    }
                    else{
                        $dataOrderMikrotik = array_merge($dataOrderMikrotik,["type" => OrderMikrotikEnum::TYPE_HOTSPOT]);
                    }

                    $this->orderMikrotik->updateOrCreate([
                        'order_item_id' => $orderItem->id,
                    ],$dataOrderMikrotik);
                }
            }
            
            $total -= $discount;

            if($total <= 0){
                DB::rollBack();
                return $this->response(false, "Total transaksi tidak boleh kurang dari samadengan 0");
            }

            $settingFee = SettingHelper::checkSettingFee($result->id);

            if($settingFee["IsError"] == TRUE){
                DB::rollBack();
                return $this->response(false, $settingFee["Message"]);
            }
            else{
                $settingFee = $settingFee["Data"];
            }

            $result->update([
                'owner_fee' => $settingFee["owner_fee"],
                'agen_fee' => $settingFee["agen_fee"],
                'total_owner_fee' => $settingFee["total_owner_fee"],
                'total_agen_fee' => $settingFee["total_agen_fee"],
                'customer_type_fee' => $settingFee["customer_type_fee"],
                'customer_value_fee' => $settingFee["customer_value_fee"],
                'customer_total_fee' => $settingFee["customer_total_fee"],
            ]);

            $deteleOrderItem = $this->orderItem;
            $deteleOrderItem = $deteleOrderItem->where("order_id",$id);
            $deteleOrderItem = $deteleOrderItem->whereNotIn("product_id",$productIdNotDelete);
            $deteleOrderItem = $deteleOrderItem->delete();

            DB::commit();

            return $this->response(true, 'Berhasil mengubah data',$result);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->order->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function proofOrder(ProofOrderRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $proof_order = $request->file("proof_order");
            $payment_note = $request->payment_note;
            $status = $request->status;

            $result = $this->order;
            $result = $result->findOrFail($id);

            if ($proof_order) {
                $upload = UploadHelper::upload_file($proof_order, 'manual-payments', OrderEnum::PROOF_ORDER_EXT);

                if ($upload["IsError"] == TRUE) {
                    return $this->response(false, $upload["Message"]);
                }

                $proof_order = $upload["Path"];
            }

            $owners = $this->user;
            $owners = $owners->role([RoleEnum::OWNER]);
            $owners = $owners->get();

            if(request()->routeIs("landing-page.manual-payments.proofOrder")){
                Notification::send($owners,new OrderNotification(route('dashboard.orders.show',$result->id),"Pembayaran Pesanan","Terdapat pembayaran order #".$result->code.". Silahkan ubah transaksi menjadi selesai ketika data sudah benar",$result));
            }else{
                if(Auth::user()->hasRole([RoleEnum::AGEN,RoleEnum::ADMIN_AGEN])){    
                    Notification::send($owners,new OrderNotification(route('dashboard.orders.show',$result->id),"Pembayaran Pesanan","Terdapat pembayaran order #".$result->code.". Silahkan ubah transaksi menjadi selesai ketika data sudah benar",$result));
                }
            }

            $result->update([
                'proof_order' => $proof_order,
                'payment_note' => $payment_note,
                'status' => $status,
            ]);

            DB::commit();

            return $this->response(true, 'Berhasil upload bukti pembayaran',$result);
        } catch (Throwable $th) {
            DB::rollBack();;
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function updateStatus(UpdateStatusRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $status = (empty($request->status)) ? null : trim(strip_tags($request->status));

            $result = $this->order;
            $result = $result->where("id",$id);
            $result = $result->firstOrFail();

            $result->update([
                'status' => $status
            ]);

            if($status == OrderEnum::STATUS_SUCCESS){
                $result->update([
                    'paid_date' => date("Y-m-d H:i:s")
                ]);
            }

            WhatsappHelper::sendWhatsappOrderTemplate($result->id);

            DB::commit();

            return $this->response(true, 'Berhasil mengubah status transaksi',$result);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function updateProvider(UpdateProviderRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $provider_id = (empty($request->provider_id)) ? null : trim(strip_tags($request->provider_id));

            $result = $this->order;
            $result = $result->where("id",$id);
            $result = $result->firstOrFail();

            $result->update([
                'provider_id' => $provider_id
            ]);

            if($result->provider->type == ProviderEnum::TYPE_DOKU){
                $checkoutDoku = PaymentHelper::checkoutDoku($result->id);

                if($checkoutDoku["IsError"] == TRUE){
                    DB::rollBack();
                    return $this->response(false, $checkoutDoku["Message"]);
                }
            }

            if(in_array($result->provider->type,[ProviderEnum::TYPE_DOKU,ProviderEnum::TYPE_MANUAL_TRANSFER])){

                OrderExpiredJob::dispatch($result->id)->delay(now()->addMinutes((env("DOKU_DUE_DATE")+1)));

                $result->update([
                    'status' => OrderEnum::STATUS_WAITING_PAYMENT,
                    'progress' => OrderEnum::PROGRESS_DRAFT,
                    'expired_date' => date("YmdHis",strtotime(date("Y-m-d H:i:s")." + ".(env("DOKU_DUE_DATE")+1)."minutes")),
                ]);

                WhatsappHelper::sendWhatsappOrderTemplate($result->id,"pesanan");
            }

            DB::commit();

            return $this->response(true, 'Checkout berhasil dilakukan. Silahkan lakukan pembayaran sampai batas waktu yang telah ditentukan',$result);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function updateProgress(UpdateProgressRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $progress = (!isset($request->progress)) ? null : trim(strip_tags($request->progress));

            $result = $this->order;
            $result = $result->where("id",$id);
            $result = $result->firstOrFail();

            $result->update([
                'progress' => $progress
            ]);

            WhatsappHelper::sendWhatsappOrderTemplate($result->id,"progress");

            DB::commit();

            return $this->response(true, 'Berhasil mengubah progress transaksi',$result);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    private function addItemOrderPayLater($request){
        $data = [];
        try {
            $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));
            $customer_phone = (empty($request->customer_phone)) ? null : trim(strip_tags($request->customer_phone));
            $repeater = $request->repeater;

            $checkExistOrderPayLater = $this->order;
            $checkExistOrderPayLater = $checkExistOrderPayLater->where("business_id",$business_id);
            $checkExistOrderPayLater = $checkExistOrderPayLater->where("customer_phone",$customer_phone);
            $checkExistOrderPayLater = $checkExistOrderPayLater->whereHas("provider",function($query2){
                $query2->where("type",ProviderEnum::TYPE_PAY_LATER);
            });
            $checkExistOrderPayLater = $checkExistOrderPayLater->where("status",OrderEnum::STATUS_PAY_LATER);
            $checkExistOrderPayLater = $checkExistOrderPayLater->orderBy("created_at","DESC");
            $checkExistOrderPayLater = $checkExistOrderPayLater->first();

            if($checkExistOrderPayLater){

                $discount = $checkExistOrderPayLater->discount;
                $total = 0;

                foreach($repeater as $index => $row){
                    $product_id = $row["product_id"] ?? null;
                    $qty = $row["qty"] ?? null;
                    $disc = $row["discount"] ?? 0;
                    $username = $row["username"] ?? null;
                    $password = $row["password"] ?? null;
                    $service = $row["service"] ?? null;
                    $server = $row["server"] ?? null;
                    $profile = $row["profile"] ?? null;
                    $local_address = $row["local_address"] ?? null;
                    $remote_address = $row["remote_address"] ?? null;
                    $comment = $row["comment"] ?? null;
                    $time_limit = $row["time_limit"] ?? null;
                    $auto_userpassword = $row["auto_userpassword"] ?? null;
                    $address = $row["address"] ?? null;
                    $mac_address = $row["mac_address"] ?? null;
                    $expired_date = $row["expired_date"] ?? null;

                    if(empty($product_id)){
                        $data["IsError"] = TRUE;
                        $data["Data"] = null;
                        $data["Message"] = "Product ID tidak boleh kosong";
                        goto ResultData;
                    }

                    if(empty($qty)){
                        $data["IsError"] = TRUE;
                        $data["Data"] = null;
                        $data["Message"] = "Qty produk tidak boleh kosong";
                        goto ResultData;
                    }

                    if($qty <= 0){
                        $data["IsError"] = TRUE;
                        $data["Data"] = null;
                        $data["Message"] = "Qty produk tidak boleh kosong";
                        goto ResultData;
                    }

                    $productResult = $this->product;
                    $productResult = $productResult->where("id",$product_id);
                    $productResult = $productResult->first();

                    if(!$productResult){
                        $data["IsError"] = TRUE;
                        $data["Data"] = null;
                        $data["Message"] = "Produk tidak ditemukan";
                        goto ResultData;
                    }

                    if($productResult->price <= 0){
                        $data["IsError"] = TRUE;
                        $data["Data"] = null;
                        $data["Message"] = "Harga produk ".$productResult->name." belum diatur";
                        goto ResultData;
                    }

                    if($checkExistOrderPayLater->business->category->name == BusinessCategoryEnum::MIKROTIK){
                        
                        if(empty($profile)){
                            $data["IsError"] = TRUE;
                            $data["Data"] = null;
                            $data["Message"] = "Profile tidak boleh kosong";
                            goto ResultData;
                        }

                        if($productResult->mikrotik == ProductEnum::MIKROTIK_PPPOE){
                            if(empty($service)){
                                $data["IsError"] = TRUE;
                                $data["Data"] = null;
                                $data["Message"] = "Service tidak boleh kosong";
                                goto ResultData;
                            }
                            if(empty($local_address)){
                                $data["IsError"] = TRUE;
                                $data["Data"] = null;
                                $data["Message"] = "Local address tidak boleh kosong";
                                goto ResultData;
                            }
                            if(empty($remote_address)){
                                $data["IsError"] = TRUE;
                                $data["Data"] = null;
                                $data["Message"] = "Remote address tidak boleh kosong";
                                goto ResultData;
                            }
                        }
                        else{
                            if(empty($server)){
                                $data["IsError"] = TRUE;
                                $data["Data"] = null;
                                $data["Message"] = "Server tidak boleh kosong";
                                goto ResultData;
                            }

                            if(!isset($time_limit)){
                                $data["IsError"] = TRUE;
                                $data["Data"] = null;
                                $data["Message"] = "Time limit tidak boleh kosong";
                                goto ResultData;
                            }

                            if(!isset($auto_userpassword)){
                                $data["IsError"] = TRUE;
                                $data["Data"] = null;
                                $data["Message"] = "Jenis pengisian tidak boleh kosong";
                                goto ResultData;
                            }

                            if($auto_userpassword == OrderMikrotikEnum::AUTO_USERPASSWORD_TRUE){
                                $username = Str::random(6);
                                $password = $username;
                            }
                        }

                        if(empty($username)){
                            $data["IsError"] = TRUE;
                            $data["Data"] = null;
                            $data["Message"] = "Username tidak boleh kosong";
                            goto ResultData;
                        }
                        
                        if(empty($password)){
                            $data["IsError"] = TRUE;
                            $data["Data"] = null;
                            $data["Message"] = "Password tidak boleh kosong";
                            goto ResultData;
                        }
                    }

                    if($productResult->is_using_stock == ProductEnum::IS_USING_STOCK_TRUE){
                        $stockReady = $productResult->stocks()->where("type",ProductStockEnum::TYPE_MASUK)->sum("qty") - $productResult->stocks()->where("type",ProductStockEnum::TYPE_KELUAR)->sum("qty");

                        if($stockReady <= 0){
                            $data["IsError"] = TRUE;
                            $data["Data"] = null;
                            $data["Message"] = "Stok produk ".$productResult->name." belum diatur";
                            goto ResultData;
                        }

                        if($stockReady < $qty){
                            $data["IsError"] = TRUE;
                            $data["Data"] = null;
                            $data["Message"] = "Stok produk ".$productResult->name." tinggal ".$stockReady;
                            goto ResultData;
                        }
                    }
                    
                    $disc = str_replace(".","",$disc);

                    $dataOrderItem = [
                        'order_id' => $checkExistOrderPayLater->id,
                        'product_id' => $productResult->id,
                        'product_code' => $productResult->code,
                        'product_name' => $productResult->name,
                        'product_price' => $productResult->price,
                        'discount' => $disc,
                    ];

                    $orderItem = $this->orderItem;
                    $orderItem = $orderItem->where("order_id",$checkExistOrderPayLater->id);
                    $orderItem = $orderItem->where("product_id",$productResult->id);
                    $orderItem = $orderItem->first();

                    $oldQty = $orderItem->qty ?? 0;
                    $newQty = $oldQty + $qty;

                    $dataOrderItem = array_merge($dataOrderItem,["qty" => $newQty]);

                    if($orderItem){
                        $orderItem->update($dataOrderItem);

                        if($productResult->is_using_stock == ProductEnum::IS_USING_STOCK_TRUE){

                            if($oldQty != $orderItem->qty){
                                $available = $orderItem->product->stocks()->where("type",ProductStockEnum::TYPE_MASUK)->sum("qty") - $orderItem->product->stocks()->where("type",ProductStockEnum::TYPE_KELUAR)->sum("qty");

                                $this->productStock->create([
                                    'date' => date("Y-m-d"),
                                    'type' => ProductStockEnum::TYPE_MASUK,
                                    'product_id' => $productResult->id,
                                    'qty' => $oldQty,
                                    'available' => $available + $oldQty,
                                    'note' => 'Stok masuk order #'.$checkExistOrderPayLater->code,
                                ]);
        
                                $this->productStock->create([
                                    'date' => date("Y-m-d"),
                                    'type' => ProductStockEnum::TYPE_KELUAR,
                                    'product_id' => $productResult->id,
                                    'qty' => $newQty,
                                    'available' => $available - $newQty,
                                    'note' => 'Stok keluar order #'.$checkExistOrderPayLater->code,
                                ]);
                            }
                        }
                    }
                    else{
                        $orderItem = $this->orderItem->create($dataOrderItem);

                        if($productResult->is_using_stock == ProductEnum::IS_USING_STOCK_TRUE){
                            $available = $orderItem->product->stocks()->where("type",ProductStockEnum::TYPE_MASUK)->sum("qty") - $orderItem->product->stocks()->where("type",ProductStockEnum::TYPE_KELUAR)->sum("qty");

                            $this->productStock->create([
                                'date' => date("Y-m-d"),
                                'type' => ProductStockEnum::TYPE_KELUAR,
                                'product_id' => $productResult->id,
                                'qty' => $qty,
                                'available' => $available - $qty,
                                'note' => 'Stok keluar order #'.$checkExistOrderPayLater->code,
                            ]);
                        }
                    }

                    $total += ( $qty * $productResult->price ) - $disc; 
                    
                    if($checkExistOrderPayLater->business->category->name == BusinessCategoryEnum::MIKROTIK){
                        $dataOrderMikrotik = [
                            'order_item_id' => $orderItem->id,
                            'username' => $username,
                            'profile' => $profile,
                            'service' => $service,
                            'server' => $server,
                            'address' => $address,
                            'mac_address' => $mac_address,
                            'password' => $password,
                            'type' => $productResult->mikrotik,
                            'expired_date' => $expired_date,
                            'time_limit' => $time_limit,
                            'comment' => $comment,
                            'local_address' => $local_address,
                            'remote_address' => $remote_address,
                            'disabled' => OrderMikrotikEnum::DISABLED_TRUE,
                        ];

                        if($productResult->mikrotik == ProductEnum::MIKROTIK_PPPOE){
                            $dataOrderMikrotik = array_merge($dataOrderMikrotik,["type" => OrderMikrotikEnum::TYPE_PPPOE]);
                        }
                        else{
                            $dataOrderMikrotik = array_merge($dataOrderMikrotik,["type" => OrderMikrotikEnum::TYPE_HOTSPOT]);
                        }

                        $this->orderMikrotik->updateOrCreate([
                            'order_item_id' => $orderItem->id,
                        ],$dataOrderMikrotik);

                        $mikrotikConfig = SettingHelper::mikrotikConfig($checkExistOrderPayLater->business_id,$checkExistOrderPayLater->business->user_id);
                        $ip = $mikrotikConfig->ip ?? null;
                        $username = $mikrotikConfig->username ?? null;
                        $password = $mikrotikConfig->password ?? null;
                        $port = $mikrotikConfig->port ?? null;
                        
                        $connect = $this->routerosApi;
                        $connect->debug("false");

                        if(!$connect->connect($ip,$username,$password,$port)){
                            $data["IsError"] = TRUE;
                            $data["Data"] = null;
                            $data["Message"] = 'Koneksi dengan mikrotik gagal. Silahkan cek konfigurasi anda';
                            goto ResultData;
                        }

                        if($dataOrderMikrotik["type"] == OrderMikrotikEnum::TYPE_PPPOE){
                            $connect = $connect->comm('/ppp/secret/print');
                        }
                        else{
                            $connect = $connect->comm('/ip/hotspot/user/print');
                        }

                        $connectLog = LogHelper::mikrotikLog($connect);

                        if($connectLog["IsError"] == TRUE){
                            $data["IsError"] = TRUE;
                            $data["Data"] = null;
                            $data["Message"] = $connectLog["Message"];
                            goto ResultData;
                        }

                        foreach($connect as $i => $v){
                            if($v["name"] == $dataOrderMikrotik["username"]){
                                $data["IsError"] = TRUE;
                                $data["Data"] = null;
                                $data["Message"] = "Username ". $dataOrderMikrotik["username"]. " sudah terdaftar dimikrotik";
                                goto ResultData;
                            }
                        }
                    }
                }
                $total -= $discount;

                if($total <= 0){
                    $data["IsError"] = TRUE;
                    $data["Data"] = null;
                    $data["Message"] = "Total transaksi tidak boleh kurang dari samadengan 0";
                    goto ResultData;
                }
    
                $settingFee = SettingHelper::checkSettingFee($checkExistOrderPayLater->id);
    
                if($settingFee["IsError"] == TRUE){
                    $data["IsError"] = TRUE;
                    $data["Data"] = null;
                    $data["Message"] = $settingFee["Message"];
                    goto ResultData;
                }
                else{
                    $settingFee = $settingFee["Data"];
                }
                
                $checkExistOrderPayLater->update([
                    'owner_fee' => $settingFee["owner_fee"],
                    'agen_fee' => $settingFee["agen_fee"],
                    'total_owner_fee' => $settingFee["total_owner_fee"],
                    'total_agen_fee' => $settingFee["total_agen_fee"],
                    'customer_type_fee' => $settingFee["customer_type_fee"],
                    'customer_value_fee' => $settingFee["customer_value_fee"],
                    'customer_total_fee' => $settingFee["customer_total_fee"],
                ]);

                $data["IsError"] = FALSE;
                $data["Data"] = $checkExistOrderPayLater;
                $data["Message"] = "Checkout berhasil dilakukan. Silahkan lakukan pembayaran sampai batas waktu yang telah ditentukan";
                goto ResultData;
            }else{
                $data["IsError"] = FALSE;
                $data["Data"] = null;
                $data["Message"] = "Customer belum memiliki pesanan bayar nanti";
                goto ResultData;
            }

        } catch (\Throwable $th) {
            $data["IsError"] = TRUE;
            $data["Data"] = null;
            $data["Message"] = $th->getMessage();
            goto ResultData;
        }

        ResultData:
        return $data;
    }
    
}
