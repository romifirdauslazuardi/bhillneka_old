<?php

namespace App\Services;

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

    public function store(StoreRequest $request)
    {
        try {
            $product_id = (empty($request->product_id)) ? null : trim(strip_tags($request->product_id));
            $qty = (empty($request->qty)) ? 0 : trim(strip_tags($request->qty));
            $note = (empty($request->note)) ? null : trim(strip_tags($request->note));

            $create = $this->productStock->create([
                'product_id' => $product_id,
                'qty' => $qty,
                'note' => $note,
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
            $qty = (empty($request->qty)) ? 0 : trim(strip_tags($request->qty));
            $note = (empty($request->note)) ? null : trim(strip_tags($request->note));

            $result = $this->productStock->findOrFail($id);

            $result->update([
                'qty' => $qty,
                'note' => $note,
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
            $result = $this->productStock->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
    
}
