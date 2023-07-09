<?php

namespace App\Services;

use App\Enums\BusinessCategoryEnum;
use App\Enums\OrderEnum;
use App\Enums\OrderMikrotikEnum;
use App\Enums\ProductEnum;
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
use App\Jobs\OrderJob;
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

            $order = $this->order->create([
                'code' => CodeHelper::generateOrder(),
                'user_id' => $user_id,
                'customer_id' => $customer_id,
                'customer_name' => $customer_name,
                'customer_phone' => $customer_phone,
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
                    if($productResult->mikrotik == ProductEnum::MIKROTIK_PPPOE){
                        if(empty($service)){
                            DB::rollBack();
                            return $this->response(false, "Service tidak boleh kosong");
                        }
                        if(empty($profile)){
                            DB::rollBack();
                            return $this->response(false, "Profile tidak boleh kosong");
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
                }

                if($business->category->name == BusinessCategoryEnum::MIKROTIK){
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
                            $this->productStock->create([
                                'product_id' => $productResult->id,
                                'qty' => $oldQty,
                                'note' => 'Stok masuk order #'.$order->code,
                                'author_id' => $author_id,
                            ]);
    
                            $this->productStock->create([
                                'product_id' => $productResult->id,
                                'qty' => -$orderItem->qty,
                                'note' => 'Stok keluar order #'.$order->code,
                                'author_id' => $author_id,
                            ]);
                        }
                    }
                }
                else{
                    $orderItem = $this->orderItem->create($dataOrderItem);

                    if($productResult->is_using_stock == ProductEnum::IS_USING_STOCK_TRUE){
                        $this->productStock->create([
                            'product_id' => $productResult->id,
                            'qty' => -$qty,
                            'note' => 'Stok keluar order #'.$order->code,
                            'author_id' => $author_id,
                        ]);
                    }
                }

                $total += ( $qty * $productResult->price ) - $disc; 
                
                if($business->category->name == BusinessCategoryEnum::MIKROTIK){
                    $dataOrderMikrotik = [
                        'order_item_id' => $orderItem->id,
                        'username' => $username,
                        'profile' => $profile,
                        'service' => $service,
                        'server' => $server,
                        'address' => $address,
                        'mac_address' => $mac_address,
                        'password' => $password,
                        'type' => $type,
                        'expired_date' => $expired_date,
                        'time_limit' => $time_limit,
                        'comment' => $comment,
                        'local_address' => $local_address,
                        'remote_address' => $remote_address,
                        'disabled' => OrderMikrotikEnum::DISABLED_TRUE,
                        'author_id' => Auth::user()->id,
                    ];

                    if($productResult->mikrotik == ProductEnum::MIKROTIK_PPPOE){
                        $dataOrderMikrotik["type"] = OrderMikrotikEnum::TYPE_PPPOE;
                    }
                    else{
                        $dataOrderMikrotik["type"] = OrderMikrotikEnum::TYPE_HOTSPOT;
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

                    Log::info($connect);

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

            $settingFee = SettingHelper::checkSettingFee();

            if($settingFee["IsError"] == TRUE){
                DB::rollBack();
                return $this->response(false, $settingFee["Message"]);
            }
            else{
                $settingFee = $settingFee["Data"];
            }

            $order->update([
                'owner_fee' => $settingFee->owner_fee,
                'agen_fee' => $settingFee->agen_fee,
            ]);

            if($order->provider->type == ProviderEnum::TYPE_DOKU){
                $checkoutDoku = PaymentHelper::checkoutDoku($order);

                if($checkoutDoku["IsError"] == TRUE){
                    DB::rollBack();
                    return $this->response(false, $checkoutDoku["Message"]);
                }
            }

            OrderJob::dispatch($order->id)->delay(now()->addMinutes((env("DOKU_DUE_DATE")+1)));

            self::sendWhatsapp($order->id,"pesanan");

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
                $disabled = $row["disabled"] ?? null;
                $auto_userpassword = $row["auto_userpassword"] ?? null;
                $expired_date = $row["expired_date"] ?? null;

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
                    if($productResult->mikrotik == ProductEnum::MIKROTIK_PPPOE){
                        if(empty($service)){
                            DB::rollBack();
                            return $this->response(false, "Service tidak boleh kosong");
                        }
                        if(empty($profile)){
                            DB::rollBack();
                            return $this->response(false, "Profile tidak boleh kosong");
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
                        
                        if(empty($mikrotik_id)){
                            if($auto_userpassword == OrderMikrotikEnum::AUTO_USERPASSWORD_TRUE){
                                if(empty($username) && empty($password)){
                                    $username = Str::random(6);
                                    $password = $username;
                                }
                            }
                        }
                    }

                    if(!isset($auto_userpassword)){
                        DB::rollBack();
                        return $this->response(false, "Jenis pengisian tidak boleh kosong");
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
                            $this->productStock->create([
                                'product_id' => $productResult->id,
                                'qty' => $oldQty,
                                'note' => 'Stok masuk order #'.$result->code,
                                'author_id' => $author_id,
                            ]);
    
                            $this->productStock->create([
                                'product_id' => $productResult->id,
                                'qty' => -$orderItem->qty,
                                'note' => 'Stok keluar order #'.$result->code,
                                'author_id' => $author_id,
                            ]);
                        }
                    }
                }
                else{
                    $orderItem = $this->orderItem->create($dataOrderItem);

                    if($productResult->is_using_stock == ProductEnum::IS_USING_STOCK_TRUE){
                        $this->productStock->create([
                            'product_id' => $productResult->id,
                            'qty' => -$qty,
                            'note' => 'Stok keluar order #'.$result->code,
                            'author_id' => $author_id,
                        ]);
                    }
                }

                $productIdNotDelete[] = $productResult->id;
                
                $total += ( $qty * $productResult->price ) - $disc; 
                
                if($business->category->name == BusinessCategoryEnum::MIKROTIK){

                    $dataOrderMikrotik = [
                        'order_item_id' => $orderItem->id,
                        'username' => $username,
                        'profile' => $profile,
                        'service' => $service,
                        'server' => $server,
                        'password' => $password,
                        'type' => $type,
                        'expired_date' => $expired_date,
                        'time_limit' => $time_limit,
                        'comment' => $comment,
                        'local_address' => $local_address,
                        'remote_address' => $remote_address,
                        'disabled' => $disabled,
                        'author_id' => $author_id,
                    ];

                    if($productResult->mikrotik == ProductEnum::MIKROTIK_PPPOE){
                        $dataOrderMikrotik["type"] = OrderMikrotikEnum::TYPE_PPPOE;
                    }
                    else{
                        $dataOrderMikrotik["type"] = OrderMikrotikEnum::TYPE_HOTSPOT;
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

            $settingFee = SettingHelper::checkSettingFee();

            if($settingFee["IsError"] == TRUE){
                DB::rollBack();
                return $this->response(false, $settingFee["Message"]);
            }
            else{
                $settingFee = $settingFee["Data"];
            }

            $result->update([
                'owner_fee' => $settingFee->owner_fee,
                'agen_fee' => $settingFee->agen_fee,
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

            self::sendWhatsapp($result->id);

            DB::commit();

            return $this->response(true, 'Berhasil mengubah status transaksi',$result);
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

            self::sendWhatsapp($result->id,"progress");

            DB::commit();

            return $this->response(true, 'Berhasil mengubah progress transaksi',$result);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    private function sendWhatsapp($orderId,string $type = "pesanan"){
        $message = "";

        $order = $this->order;
        $order = $order->where("id",$orderId);
        $order = $order->first();

        if($type == "pesanan"){
            // if($order->status == OrderEnum::STATUS_WAITING_PAYMENT){
            //     $message .= "Selesaikan Pembayaran Anda sebelum ".date("d F Y H:i:s",strtotime($order->expired_date))." WIB";
            //     $message .= "\r\n";
            // }
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
            if(!empty($row->order_mikrotik->mikrotik_id)){
                $message .= "\r\n";
                if($row->order_mikrotik->type == OrderMikrotikEnum::TYPE_HOTSPOT){
                    $message .= "SSID : ".$row->order_mikrotik->server;
                    $message .= "\r\n";
                }
                $message .= "Username : ".$row->order_mikrotik->username;
                $message .= "\r\n";
                $message .= "Password : ".$row->order_mikrotik->password;
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
