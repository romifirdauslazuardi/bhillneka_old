<?php

namespace App\Services;

use App\Services\BaseService;
use App\Http\Requests\ProductCategory\StoreRequest;
use App\Http\Requests\ProductCategory\UpdateRequest;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Enums\RoleEnum;
use Auth;
use DB;
use Log;
use Throwable;

class ProductCategoryService extends BaseService
{
    protected $productCategory;

    public function __construct()
    {
        $this->productCategory = new ProductCategory();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));
        $business_category_id = (empty($request->business_category_id)) ? null : trim(strip_tags($request->business_category_id));

        $table = $this->productCategory;
        if (!empty($search)) {
            $table = $this->productCategory->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%');
            });
        }
        if(!empty($user_id)){
            $table = $table->where("user_id",$user_id);
        }
        if(!empty($business_category_id)){
            $table = $table->where("business_category_id",$business_category_id);
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
            $result = $this->productCategory;
            $result = $result->where('id',$id);
            $result = $result->first();

            if(!$result){
                return $this->response(false,"Data tidak ditemukan");
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
            $name = (empty($request->name)) ? null : trim(strip_tags($request->name));
            $business_category_id = (empty($request->business_category_id)) ? null : trim(strip_tags($request->business_category_id));

            $create = $this->productCategory->create([
                'name' => $name,
                'business_category_id' => $business_category_id,
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
            $name = (empty($request->name)) ? null : trim(strip_tags($request->name));
            $business_category_id = (empty($request->business_category_id)) ? null : trim(strip_tags($request->business_category_id));

            $result = $this->productCategory->findOrFail($id);

            $result->update([
                'name' => $name,
                'business_category_id' => $business_category_id,
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
            $result = $this->productCategory->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
    
}
