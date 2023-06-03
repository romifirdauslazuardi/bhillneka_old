<?php

namespace App\Services;

use App\Enums\OrderEnum;
use App\Enums\ProductEnum;
use App\Enums\RoleEnum;
use App\Helpers\CodeHelper;
use App\Services\BaseService;
use App\Http\Requests\Order\StoreRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use Throwable;

class OrderService extends BaseService
{
    protected $order;
    protected $orderItem;
    protected $product;

    public function __construct()
    {
        $this->order = new Order();
        $this->orderItem = new OrderItem();
        $this->product = new Product();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = $request->search;
        $user_id = $request->user_id;
        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        if(Auth::user()->hasRole([RoleEnum::AGEN])){
            $user_id = Auth::user()->id;
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
        if(!empty($status)){
            $table = $table->where("status",$status);
        }
        if(!empty($from_date)){
            $table = $table->whereDate("created_at",">=",$from_date);
        }
        if(!empty($to_date)){
            $table = $table->whereDate("created_at","<=",$to_date);
        }
        if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
            $table = $table->where("user_id",Auth::user()->user_id);
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

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->user_id;
            $customer_id = $request->customer_id;
            $provider_id = $request->provider_id;
            $note = $request->note;
            $repeater = $request->repeater;
            $discount = $request->discount;

            $discount = str_replace(".","",$discount);

            $create = $this->order->create([
                'code' => CodeHelper::generateOrder(),
                'user_id' => $user_id,
                'customer_id' => $customer_id,
                'provider_id' => $provider_id,
                'note' => $note,
                'discount' => $discount,
                'status' => OrderEnum::STATUS_WAITING_PAYMENT,
                'author_id' => Auth::user()->id,
            ]);

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
                        return $this->response(false, "Stok produk ".$productResult->name." belum diatur");
                    }
                }

                $disc = str_replace(".","",$disc);

                $orderItem = $this->orderItem->updateOrCreate([
                    'order_id' => $create->id,
                    'product_id' => $productResult->id,
                ],[
                    'order_id' => $create->id,
                    'product_id' => $productResult->id,
                    'product_code' => $productResult->code,
                    'product_name' => $productResult->name,
                    'product_price' => $productResult->price,
                    'qty' => $qty,
                    'discount' => $disc,
                ]);

                if($orderItem->wasRecentlyCreated){
                    $orderItem->update([
                        'author_id' => Auth::user()->id 
                    ]);
                }
            }

            $create->update([
                'fee' => 0
            ]);

            DB::commit();

            return $this->response(true, 'Berhasil menambahkan data',$create);
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
            $customer_id = $request->customer_id;
            $provider_id = $request->provider_id;
            $note = $request->note;
            $repeater = $request->repeater;
            $status = $request->status;
            $discount = $request->discount;

            $result = $this->order->findOrFail($id);

            $discount = str_replace(".","",$discount);

            $result->update([
                'customer_id' => $customer_id,
                'provider_id' => $provider_id,
                'note' => $note,
                'discount' => $discount,
                'status' => $status,
            ]);

            $productIdNotDelete = [];
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

                $orderItem = $this->orderItem->updateOrCreate([
                    'order_id' => $id,
                    'product_id' => $productResult->id,
                ],[
                    'order_id' => $id,
                    'product_id' => $productResult->id,
                    'product_code' => $productResult->code,
                    'product_name' => $productResult->name,
                    'product_price' => $productResult->price,
                    'qty' => $qty,
                    'discount' => $disc,
                ]);

                if($orderItem->wasRecentlyCreated){
                    $orderItem->update([
                        'author_id' => Auth::user()->id 
                    ]);
                }

                $productIdNotDelete[] = $productResult->id;
            }

            $result->update([
                'fee' => 0
            ]);

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
    
}
