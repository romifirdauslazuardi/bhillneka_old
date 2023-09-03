<?php

namespace App\Services;

use App\Enums\ProductStockEnum;
use App\Http\Requests\ProductStock\ImportRequest;
use App\Services\BaseService;
use App\Http\Requests\ProductStock\StoreRequest;
use App\Http\Requests\ProductStock\UpdateRequest;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use Throwable;

class ProductStockService extends BaseService
{
    protected $product;
    protected $productStock;

    public function __construct()
    {
        $this->product = new Product();
        $this->productStock = new ProductStock();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $product_id = (empty($request->product_id)) ? null : trim(strip_tags($request->product_id));
        $type = (empty($request->type)) ? null : trim(strip_tags($request->type));

        $table = $this->productStock;
        if(!empty($product_id)){
            $table = $table->where("product_id",$product_id);
        }
        if(!empty($type)){
            $table = $table->where("type",$type);
        }

        if ($paginate) {
            $table = $table->orderBy('created_at', 'DESC');
            $table = $table->paginate(10);
            $table = $table->withQueryString();
        } else {
            $table = $table->orderBy('created_at', 'ASC');
            $table = $table->get();
        }

        return $this->response(true, 'Berhasil mendapatkan data', $table);
    }

    public function show($id)
    {
        try {
            $result = $this->productStock;
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

    public function store(StoreRequest $request)
    {
        try {
            $type = (empty($request->type)) ? null : trim(strip_tags($request->type));
            $product_id = (empty($request->product_id)) ? null : trim(strip_tags($request->product_id));
            $qty = (empty($request->qty)) ? null : trim(strip_tags($request->qty));
            $note = (empty($request->note)) ? null : trim(strip_tags($request->note));
            $date = (empty($request->date)) ? null : trim(strip_tags($request->date));

            $productResult = $this->product;
            $productResult = $productResult->where("id",$product_id);
            $productResult = $productResult->first();

            $available = $productResult->stocks()->where("type",ProductStockEnum::TYPE_MASUK)->sum("qty") - $productResult->stocks()->where("type",ProductStockEnum::TYPE_KELUAR)->sum("qty");

            if($type == ProductStockEnum::TYPE_MASUK){
                $available += $qty;
            }
            else{
                $available -= $qty;
            }

            $create = $this->productStock->create([
                'type' => $type,
                'product_id' => $product_id,
                'qty' => $qty,
                'available' => $available,
                'note' => $note,
                'date' => $date,
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
            $type = (empty($request->type)) ? null : trim(strip_tags($request->type));
            $qty = (empty($request->qty)) ? null : trim(strip_tags($request->qty));
            $note = (empty($request->note)) ? null : trim(strip_tags($request->note));
            $date = (empty($request->date)) ? null : trim(strip_tags($request->date));

            $result = $this->productStock->findOrFail($id);

            $result->update([
                'type' => $type,
                'qty' => $qty,
                'note' => $note,
                'date' => $date,
            ]);

            return $this->response(true, 'Berhasil mengubah data',$result);
        } catch (Throwable $th) {
            DB::beginTransaction();
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->productStock->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
