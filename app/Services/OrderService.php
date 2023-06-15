<?php

namespace App\Services;

use App\Enums\OrderEnum;
use App\Enums\ProductEnum;
use App\Enums\ProviderEnum;
use App\Enums\RoleEnum;
use App\Helpers\CodeHelper;
use App\Helpers\PaymentHelper;
use App\Helpers\SettingHelper;
use App\Helpers\UploadHelper;
use App\Http\Requests\Order\ProofOrderRequest;
use App\Services\BaseService;
use App\Http\Requests\Order\StoreRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderDoku;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\SettingFee;
use App\Models\User;
use App\Notifications\OrderNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use App\Jobs\OrderJob;
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

    public function __construct()
    {
        $this->order = new Order();
        $this->orderItem = new OrderItem();
        $this->orderDoku = new OrderDoku;
        $this->product = new Product();
        $this->settingFee = new SettingFee();
        $this->user = new User();
        $this->productStock = new ProductStock();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));
        $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
        $customer_id = (empty($request->customer_id)) ? null : trim(strip_tags($request->customer_id));
        $status = (empty($request->status)) ? null : trim(strip_tags($request->status));
        $from_date = (empty($request->from_date)) ? null : trim(strip_tags($request->from_date));
        $to_date = (empty($request->to_date)) ? null : trim(strip_tags($request->to_date));

        if(Auth::user()->hasRole([RoleEnum::AGEN])){
            $user_id = Auth::user()->id;
        }
        if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
            $user_id = Auth::user()->user_id;
        }
        if(Auth::user()->hasRole([RoleEnum::USER])){
            $customer_id = Auth::user()->id;
        }

        $table = $this->order;
        if (!empty($search)) {
            $table = $this->order->where(function ($query2) use ($search) {
                $query2->where('code', 'like', '%' . $search . '%');
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
            if(Auth::user()->hasRole([RoleEnum::USER])){
                $result = $result->where("customer_id",Auth::user()->id);
            }
            $result = $result->where('id',$id);
            $result = $result->first();

            if(!$result){
                return $this->response(false, "Data tidak ditemukan");
            }

            $related = $this->order;
            $related = $related->where("user_id",$result->user_id);
            $related = $related->where("id","!=",$result->id);
            $related = $related->orderBy("created_at","DESC");
            $related = $related->get();

            $result->related = $related;

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
            $provider_id = (empty($request->provider_id)) ? null : trim(strip_tags($request->provider_id));
            $note = (empty($request->note)) ? null : trim(strip_tags($request->note));
            $repeater = $request->repeater;
            $discount = (empty($request->discount)) ? $request->discount : 0;
            $author_id = Auth::user()->id ?? null;

            $discount = str_replace(".","",$discount);
            $discount = (int)$discount;

            $order = $this->order->create([
                'code' => CodeHelper::generateOrder(),
                'user_id' => $user_id,
                'customer_id' => $customer_id,
                'provider_id' => $provider_id,
                'note' => $note,
                'discount' => (!empty($discount)) ? $discount : 0,
                'status' => OrderEnum::STATUS_WAITING_PAYMENT,
                'author_id' => $author_id,
                'expired_date' => date("YmdHis",strtotime(date("Y-m-d H:i:s")." + ".env("DOKU_DUE_DATE")."minutes"))
            ]);

            $total = 0;
            foreach($repeater as $index => $row){
                $product_id = $row["product_id"] ?? null;
                $qty = $row["qty"] ?? null;
                $disc = $row["discount"] ?? 0;

                if(empty($product_id)){
                    DB::rollback();
                    return $this->response(false, "Produk ID tidak boleh kosong");
                }
                if(empty($qty)){
                    DB::rollback();
                    return $this->response(false, "Qty produk tidak boleh kosong");
                }
                if($qty <= 0){
                    DB::rollback();
                    return $this->response(false, "Qty produk tidak boleh kosong");
                }

                $productResult = $this->product;
                $productResult = $productResult->where("id",$product_id);
                $productResult = $productResult->first();

                if(!$productResult){
                    DB::rollback();
                    return $this->response(false, "Produk tidak ditemukan");
                }

                if($productResult->price <= 0){
                    DB::rollback();
                    return $this->response(false, "Harga produk ".$productResult->name." belum diatur");
                }

                if($productResult->is_using_stock == ProductEnum::IS_USING_STOCK_TRUE){
                    $stockReady = $productResult->stocks()->sum("qty");

                    if($stockReady <= 0){
                        DB::rollback();
                        return $this->response(false, "Stok produk ".$productResult->name." belum diatur");
                    }

                    if($stockReady < $qty){
                        DB::rollback();
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
                    $this->orderItem->create($dataOrderItem);

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
            }
            
            $total -= $discount;

            if($total <= 0){
                DB::rollback();
                return $this->response(false, "Total transaksi tidak boleh kurang dari samadengan 0");
            }

            $settingFee = SettingHelper::checkSettingFee();

            if($settingFee["IsError"] == TRUE){
                DB::rollback();
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
                    DB::rollback();
                    return $this->response(false, $checkoutDoku["Message"]);
                }
            }

            OrderJob::dispatch($order->id)->delay(now()->addMinutes(env("DOKU_DUE_DATE")));

            DB::commit();

            return $this->response(true, 'Checkout berhasil dilakukan. Silahkan lakukan pembayaran sampai batas waktu yang telah ditentukan',$order);
        } catch (Throwable $th) {
            DB::rollback();
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function update(UpdateRequest $request,$id)
    {
        DB::beginTransaction();
        try {
            $customer_id = (empty($request->customer_id)) ? null : trim(strip_tags($request->customer_id));
            $provider_id = (empty($request->provider_id)) ? null : trim(strip_tags($request->provider_id));
            $note = (empty($request->note)) ? null : trim(strip_tags($request->note));
            $repeater = $request->repeater;
            $status = (empty($request->status)) ? null : trim(strip_tags($request->status));
            $discount = (empty($request->discount)) ? 0 : $request->discount;
            $author_id = Auth::user()->id ?? null;

            $result = $this->order->findOrFail($id);

            $old_totalNeto = $result->totalNeto();

            $discount = str_replace(".","",$discount);
            $discount = (int)$discount;

            $result->update([
                'customer_id' => $customer_id,
                'provider_id' => $provider_id,
                'note' => $note,
                'discount' => $discount,
                'status' => $status,
            ]);

            $productIdNotDelete = [];
            $total = 0;
            foreach($repeater as $index => $row){
                $product_id = $row["product_id"];
                $qty = $row["qty"];
                $disc = $row["discount"];

                if(empty($product_id)){
                    DB::rollback();
                    return $this->response(false, "Produk ID tidak boleh kosong");
                }
                if(empty($qty)){
                    DB::rollback();
                    return $this->response(false, "Qty produk tidak boleh kosong");
                }
                if($qty <= 0){
                    DB::rollback();
                    return $this->response(false, "Qty produk tidak boleh kosong");
                }

                $productResult = $this->product;
                $productResult = $productResult->where("id",$product_id);
                $productResult = $productResult->first();

                if(!$productResult){
                    DB::rollback();
                    return $this->response(false, "Produk tidak ditemukan");
                }

                if($productResult->price <= 0){
                    DB::rollback();
                    return $this->response(false, "Harga produk ".$productResult->name." belum diatur");
                }

                if($productResult->is_using_stock == ProductEnum::IS_USING_STOCK_TRUE){
                    $stockReady = $productResult->stocks()->sum("qty");

                    if($stockReady <= 0){
                        DB::rollback();
                        return $this->response(false, "Stok produk belum diatur");
                    }
                }

                $disc = str_replace(".","",$disc);

                $dataOrderItem = [
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
                    $this->orderItem->create($dataOrderItem);

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
            }
            $total -= $discount;

            if($total <= 0){
                DB::rollback();
                return $this->response(false, "Total transaksi tidak boleh kurang dari samadengan 0");
            }

            $settingFee = SettingHelper::checkSettingFee();

            if($settingFee["IsError"] == TRUE){
                DB::rollback();
                return $this->response(false, $settingFee["Message"]);
            }
            else{
                $settingFee = $settingFee["Data"];
            }

            $result->update([
                'owner_fee' => $settingFee->owner_fee,
                'agen_fee' => $settingFee->agen_fee,
            ]);

            if($result->provider->type == ProviderEnum::TYPE_DOKU){
                if($result->status == OrderEnum::STATUS_WAITING_PAYMENT){
                    if($result->totalNeto() != $old_totalNeto){

                        $checkoutDoku = PaymentHelper::checkoutDoku($result);

                        if($checkoutDoku["IsError"] == TRUE){
                            DB::rollback();
                            return $this->response(false, $checkoutDoku["Message"]);
                        }
                    }
                }
            }

            $deteleOrderItem = $this->orderItem;
            $deteleOrderItem = $deteleOrderItem->where("order_id",$id);
            $deteleOrderItem = $deteleOrderItem->whereNotIn("product_id",$productIdNotDelete);
            $deteleOrderItem = $deteleOrderItem->delete();

            DB::commit();

            return $this->response(true, 'Berhasil mengubah data',$result);
        } catch (Throwable $th) {
            DB::rollback();
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
            DB::rollback();;
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
    
}
