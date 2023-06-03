<?php

namespace App\Services;

use App\Services\BaseService;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Helpers\SlugHelper;
use App\Enums\RoleEnum;
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
        $search = $request->search;
        $user_id = $request->user_id;
        $category_id = $request->category_id;
        $unit_id = $request->unit_id;
        $status = $request->status;

        if(Auth::user()->hasRole([RoleEnum::AGEN])){
            $user_id = Auth::user()->id;
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
        if(!empty($category_id)){
            $table = $table->where("category_id",$category_id);
        }
        if(!empty($unit_id)){
            $table = $table->where("unit_id",$unit_id);
        }
        if(isset($status)){
            $table = $table->where("status",$status);
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
            $result = $this->product;
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

            return $this->response(true, 'Berhasil mendapatkan data', $result);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function showByCode($code)
    {
        try {
            $result = $this->product;
            if(Auth::user()->hasRole([RoleEnum::AGEN])){
                $result = $result->where("user_id",Auth::user()->id);
            }
            $result = $result->where("code",$code);
            $result = $result->first();

            if(!$result){
                return $this->response(false, "Data tidak ditemukan");
            }

            $result->stock = $result->stocks()->sum("qty");

            return $this->response(true, 'Berhasil mendapatkan data', $result);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            $code = $request->code;
            $name = $request->name;
            $price = $request->price;
            $description = $request->description;
            $user_id = $request->user_id;
            $category_id = $request->category_id;
            $unit_id = $request->unit_id;
            $status = $request->status;
            $is_using_stock = $request->is_using_stock;

            $slug = SlugHelper::generate(Product::class,$name,"slug");

            $create = $this->product->create([
                'slug' => $slug,
                'name' => $name,
                'code' => $code,
                'price' => $price,
                'description' => $description,
                'user_id' => $user_id,
                'category_id' => $category_id,
                'unit_id' => $unit_id,
                'status' => $status,
                'is_using_stock' => $is_using_stock,
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
            $name = $request->name;
            $code = $request->code;
            $price = $request->price;
            $description = $request->description;
            $user_id = $request->user_id;
            $category_id = $request->category_id;
            $unit_id = $request->unit_id;
            $status = $request->status;
            $is_using_stock = $request->is_using_stock;

            $result = $this->product->findOrFail($id);

            if($name !== $result->name){
                $slug = SlugHelper::generate(Product::class,$name,"slug");
            }
            else{
                $slug = $result->slug;
            }

            $result->update([
                'slug' => $slug,
                'name' => $name,
                'code' => $code,
                'price' => $price,
                'description' => $description,
                'user_id' => $user_id,
                'category_id' => $category_id,
                'unit_id' => $unit_id,
                'status' => $status,
                'is_using_stock' => $is_using_stock,
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
