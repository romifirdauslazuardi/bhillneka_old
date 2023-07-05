<?php

namespace App\Services;

use App\Enums\ProductEnum;
use App\Services\BaseService;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Helpers\SlugHelper;
use App\Enums\RoleEnum;
use App\Helpers\UploadHelper;
use Auth;
use DB;
use Log;
use Throwable;

class ProductService extends BaseService
{
    protected $product;

    public function __construct()
    {
        $this->product = new Product();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));
        $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
        $status = (!isset($request->status)) ? null : trim(strip_tags($request->status));
        $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));
        $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));

        if(Auth::check()){
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
        if(!empty($business_id)){
            $table = $table->where("business_id",$business_id);
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
                'author_id' => Auth::user()->id,
            ]);

            return $this->response(true, 'Berhasil menambahkan data',$create);
        } catch (Throwable $th) {
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
