<?php

namespace App\Services;

use App\Enums\BusinessCategoryEnum;
use App\Enums\ProductEnum;
use App\Enums\ProductStockEnum;
use App\Services\BaseService;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Helpers\SlugHelper;
use App\Helpers\SettingHelper;
use App\Enums\RoleEnum;
use App\Enums\SettingFeeEnum;
use App\Helpers\UploadHelper;
use App\Models\RouterosAPI;
use App\Models\Business;
use Auth;
use DB;
use Log;
use Throwable;

class ProductService extends BaseService
{
    protected $product;
    protected $routerosApi;
    protected $business;
    protected $productStock;

    public function __construct()
    {
        $this->product = new Product();
        $this->routerosApi = new RouterosAPI();
        $this->business = new Business();
        $this->productStock = new ProductStock();
    }

    public function index(Request $request, bool $paginate = true,$public = false)
    {
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));
        $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
        $status = (!isset($request->status)) ? null : trim(strip_tags($request->status));
        $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));
        $is_using_stock = (empty($request->is_using_stock)) ? null : trim(strip_tags($request->is_using_stock));

        if($public == false){
            if(Auth::user()->hasRole([RoleEnum::AGEN])){
                $user_id = Auth::user()->id;
            }
    
            if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
                $user_id = Auth::user()->user_id;
            }
    
            if(!empty(Auth::user()->business_id)){
                $business_id = Auth::user()->business_id;
            }
        }

        $table = $this->product;
        if (!empty($search)) {
            $table = $this->product->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%');
                $query2->orWhere('price', 'like', '%' . $search . '%');
                $query2->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        if(!empty($user_id)){
            $table = $table->where("user_id",$user_id);
        }
        if(isset($status)){
            $table = $table->where("status",$status);
        }
        if(isset($is_using_stock)){
            $table = $table->where("is_using_stock",$is_using_stock);
        }
        if(!empty($business_id)){
            $table = $table->where("business_id",$business_id);
            if(request()->routeIs("landing-page.shops.index")){
                $business = $this->business;
                $business = $business->where("id",$business_id);
                $business = $business->first();

                if($business){
                    if($business->category->name == BusinessCategoryEnum::MIKROTIK){
                        $table = $table->where("mikrotik",ProductEnum::MIKROTIK_HOTSPOT);
                    }
                }
            }
        }
        $table = $table->orderBy('created_at', 'DESC');
        

        if ($paginate) {
            $table = $table->paginate(10);
            $table = $table->withQueryString();
        } else {
            $table = $table->get();
        }
        
        foreach($table as $index => $row){
            $stock = $row->stocks()->where("type",ProductStockEnum::TYPE_MASUK)->sum("qty") - $row->stocks()->where("type",ProductStockEnum::TYPE_KELUAR)->sum("qty");
            $row->stock = $stock;
            
            $estimationOwnerIncome = 0;
            $estimationAgenIncome = 0;

            $settingFee = SettingHelper::settingFee();

            foreach($settingFee as $i => $value){
                if($value->mark == SettingFeeEnum::MARK_KURANG_DARI){
                    if($row->price <= $value->limit){
                        $estimationOwnerIncome = ($value->owner_fee/100) * $row->price;
                        $estimationOwnerIncome = round($estimationOwnerIncome) + 5000;

                        $estimationAgenIncome = ($value->agen_fee/100) * $row->price;
                        $estimationAgenIncome = round($estimationAgenIncome) - 5000;
                    }
                }else{
                    if($row->price > $value->limit){
                        $estimationOwnerIncome = ($value->owner_fee/100) * $row->price;
                        $estimationOwnerIncome = round($estimationOwnerIncome) + 5000;

                        $estimationAgenIncome = ($value->agen_fee/100) * $row->price;
                        $estimationAgenIncome = round($estimationAgenIncome) - 5000;
                    }
                }
            }

            $row->estimationOwnerIncome = $estimationOwnerIncome;
            $row->estimationAgenIncome = $estimationAgenIncome;
        }

        return $this->response(true, 'Berhasil mendapatkan data', $table);
    }

    public function show($id)
    {
        try {
            $result = $this->product;
            if(Auth::user()->hasRole([RoleEnum::AGEN])){
                $result = $result->where("user_id",Auth::user()->id);
            }
            if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
                $result = $result->where("user_id",Auth::user()->user_id);
            }
            if(!empty(Auth::user()->business_id)){
                $result = $result->where("business_id",Auth::user()->business_id);
            }
            $result = $result->where('id',$id);
            $result = $result->first();

            if(!$result){
                return $this->response(false, "Data tidak ditemukan");
            }

            $stock = $result->stocks()->where("type",ProductStockEnum::TYPE_MASUK)->sum("qty") - $result->stocks()->where("type",ProductStockEnum::TYPE_KELUAR)->sum("qty");
            $result->stock = $stock;

            $estimationOwnerIncome = 0;
            $estimationAgenIncome = 0;

            $settingFee = SettingHelper::settingFee();

            foreach($settingFee as $i => $value){
                if($value->mark == SettingFeeEnum::MARK_KURANG_DARI){
                    if($result->price <= $value->limit){
                        $estimationOwnerIncome = ($value->owner_fee/100) * $result->price;
                        $estimationOwnerIncome = round($estimationOwnerIncome) + 5000;

                        $estimationAgenIncome = ($value->agen_fee/100) * $result->price;
                        $estimationAgenIncome = round($estimationAgenIncome) - 5000;
                    }
                }else{
                    if($result->price > $value->limit){
                        $estimationOwnerIncome = ($value->owner_fee/100) * $result->price;
                        $estimationOwnerIncome = round($estimationOwnerIncome) + 5000;

                        $estimationAgenIncome = ($value->agen_fee/100) * $result->price;
                        $estimationAgenIncome = round($estimationAgenIncome) - 5000;
                    }
                }
            }

            $result->estimationOwnerIncome = $estimationOwnerIncome;
            $result->estimationAgenIncome = $estimationAgenIncome;

            return $this->response(true, 'Berhasil mendapatkan data', $result);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function showActiveBySlug($slug)
    {
        try {
            $result = $this->product;
            $result = $result->where('slug',$slug);
            $result = $result->where("status",ProductEnum::STATUS_TRUE);
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

    public function showByCode(Request $request)
    {
        try {
            $code = (empty($request->code)) ? null : trim(strip_tags($request->code));
            $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));

            if(!empty(Auth::user()->business_id)){
                $business_id = Auth::user()->business_id;
            }

            if(!$code){
                return $this->response(false, "Kode Transaksi harus diisi");
            }

            $result = $this->product;
            $result = $result->where('code',$code);
            $result = $result->where('business_id',$business_id);
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
            $code = (empty($request->code)) ? null : trim(strip_tags($request->code));
            $name = (empty($request->name)) ? null : trim(strip_tags($request->name));
            $price = (empty($request->price)) ? 0 : trim(strip_tags($request->price));
            $description = (empty($request->description)) ? null : trim(strip_tags($request->description));;
            $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
            $weight = (empty($request->weight)) ? null : trim(strip_tags($request->weight));
            $status = (empty($request->status)) ? ProductEnum::STATUS_FALSE : trim(strip_tags($request->status));
            $is_using_stock = (empty($request->is_using_stock)) ? ProductEnum::IS_USING_STOCK_FALSE : trim(strip_tags($request->is_using_stock));
            $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));
            $mikrotik = (!isset($request->mikrotik)) ? null : trim(strip_tags($request->mikrotik));
            $profile = (empty($request->profile)) ? null : trim(strip_tags($request->profile));
            $server = (empty($request->input("server"))) ? null : trim(strip_tags($request->input("server")));
            $service = (empty($request->service)) ? null : trim(strip_tags($request->service));
            $local_address = (empty($request->local_address)) ? null : trim(strip_tags($request->local_address));
            $remote_address = (empty($request->remote_address)) ? null : trim(strip_tags($request->remote_address));
            $time_limit = (empty($request->time_limit)) ? null : trim(strip_tags($request->time_limit));
            $comment = (empty($request->comment)) ? null : trim(strip_tags($request->comment));
            $address = (empty($request->address)) ? null : trim(strip_tags($request->address));
            $mac_address = (empty($request->mac_address)) ? null : trim(strip_tags($request->mac_address));
            $expired_date = (empty($request->expired_date)) ? null : trim(strip_tags($request->expired_date));
            $qty = (empty($request->qty)) ? null : trim(strip_tags($request->qty));
            $image = $request->file("image");

            $slug = SlugHelper::generate(Product::class,$name,"slug");

            $code = str_replace(" ","-",$code);

            if ($image) {
                $upload = UploadHelper::upload_file($image, 'products', ProductEnum::IMAGE_EXT);

                if ($upload["IsError"] == TRUE) {
                    return $this->response(false, $upload["Message"]);
                }

                $image = $upload["Path"];
            }

            if(in_array($mikrotik,[ProductEnum::MIKROTIK_PPPOE,ProductEnum::MIKROTIK_HOTSPOT])){
                $mikrotikConfig = SettingHelper::mikrotikConfig();
                $ipConfig = $mikrotikConfig->ip ?? null;
                $usernameConfig = $mikrotikConfig->username ?? null;
                $passwordConfig = $mikrotikConfig->password ?? null;
                $portConfig = $mikrotikConfig->port ?? null;
                
                $connect = $this->routerosApi;
                $connect->debug("false");

                if(!$connect->connect($ipConfig,$usernameConfig,$passwordConfig,$portConfig)){
                    return $this->response(false, "Koneksi dengan mikrotik gagal. Silahkan cek konfigurasi anda");
                }
            }

            if($mikrotik == ProductEnum::MIKROTIK_HOTSPOT){
                $service = null;
                $local_address = null;
                $remote_address = null;
                $expired_date = null;

                if(!empty($address)){
                    $connect = $connect->comm('/ip/hotspot/user/print');

                    Log::info($connect);

                    foreach($connect as $index => $row){
                        if(isset($row["address"])){
                            if($address == $row["address"]){
                                return $this->response(false, "Duplikat address pada mikrotik");
                            }
                        }
                    }
                }
            }

            if($mikrotik == ProductEnum::MIKROTIK_PPPOE){
                $server = null;
                $address = null;
                $mac_address = null;
                $time_limit = null; 
            }

            $create = $this->product->create([
                'slug' => $slug,
                'name' => $name,
                'code' => $code,
                'price' => $price,
                'image' => $image,
                'description' => $description,
                'user_id' => $user_id,
                'weight' => $weight,
                'status' => $status,
                'is_using_stock' => $is_using_stock,
                'business_id' => $business_id,
                'mikrotik' => $mikrotik,
                'profile' => $profile,
                'server' => $server,
                'service' => $service,
                'local_address' => $local_address,
                'remote_address' => $remote_address,
                'time_limit' => $time_limit,
                'comment' => $comment,
                'address' => $address,
                'mac_address' => $mac_address,
                'expired_date' => $expired_date,
                'author_id' => Auth::user()->id,
            ]);

            if($create->is_using_stock == ProductEnum::IS_USING_STOCK_TRUE){
                $create = $this->productStock->create([
                    'type' => ProductStockEnum::TYPE_MASUK,
                    'product_id' => $create->id,
                    'qty' => $qty,
                    'available' => $qty,
                    'date' => date("Y-m-d"),
                    'author_id' => Auth::user()->id,
                ]);
            }

            DB::commit();

            return $this->response(true, 'Berhasil menambahkan data',$create);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $code = (empty($request->code)) ? null : trim(strip_tags($request->code));
            $name = (empty($request->name)) ? null : trim(strip_tags($request->name));
            $price = (empty($request->price)) ? 0 : trim(strip_tags($request->price));
            $description = (empty($request->description)) ? null : trim(strip_tags($request->description));;
            $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
            $weight = (empty($request->weight)) ? null : trim(strip_tags($request->weight));
            $status = (empty($request->status)) ? ProductEnum::STATUS_FALSE : trim(strip_tags($request->status));
            $is_using_stock = (empty($request->is_using_stock)) ? ProductEnum::IS_USING_STOCK_FALSE : trim(strip_tags($request->is_using_stock));
            $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));
            $mikrotik = (!isset($request->mikrotik)) ? null : trim(strip_tags($request->mikrotik));
            $profile = (empty($request->profile)) ? null : trim(strip_tags($request->profile));
            $server = (empty($request->input("server"))) ? null : trim(strip_tags($request->input("server")));
            $service = (empty($request->service)) ? null : trim(strip_tags($request->service));
            $local_address = (empty($request->local_address)) ? null : trim(strip_tags($request->local_address));
            $remote_address = (empty($request->remote_address)) ? null : trim(strip_tags($request->remote_address));
            $time_limit = (empty($request->time_limit)) ? null : trim(strip_tags($request->time_limit));
            $comment = (empty($request->comment)) ? null : trim(strip_tags($request->comment));
            $address = (empty($request->address)) ? null : trim(strip_tags($request->address));
            $mac_address = (empty($request->mac_address)) ? null : trim(strip_tags($request->mac_address));
            $expired_date = (empty($request->expired_date)) ? null : trim(strip_tags($request->expired_date));
            $image = $request->file("image");

            $result = $this->product->findOrFail($id);

            if($name !== $result->name){
                $slug = SlugHelper::generate(Product::class,$name,"slug");
            }
            else{
                $slug = $result->slug;
            }

            $code = str_replace(" ","-",$code);

            if ($image) {
                $upload = UploadHelper::upload_file($image, 'products', ProductEnum::IMAGE_EXT);

                if ($upload["IsError"] == TRUE) {
                    return $this->response(false, $upload["Message"]);
                }

                $image = $upload["Path"];
            }
            else{
                $image = $result->image;
            }

            if($mikrotik == ProductEnum::MIKROTIK_HOTSPOT){
                $service = null;
                $local_address = null;
                $remote_address = null;
                $expired_date = null;
            }

            if($mikrotik == ProductEnum::MIKROTIK_PPPOE){
                $server = null;
                $address = null;
                $mac_address = null;
                $time_limit = null; 
            }

            $result->update([
                'slug' => $slug,
                'name' => $name,
                'code' => $code,
                'price' => $price,
                'image' => $image,
                'description' => $description,
                'user_id' => $user_id,
                'weight' => $weight,
                'status' => $status,
                'is_using_stock' => $is_using_stock,
                'business_id' => $business_id,
                'mikrotik' => $mikrotik,
                'profile' => $profile,
                'server' => $server,
                'service' => $service,
                'local_address' => $local_address,
                'remote_address' => $remote_address,
                'time_limit' => $time_limit,
                'comment' => $comment,
                'address' => $address,
                'mac_address' => $mac_address,
                'expired_date' => $expired_date,
            ]);

            return $this->response(true, 'Berhasil mengubah data',$result);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->product->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
    
}
