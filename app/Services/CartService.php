<?php

namespace App\Services;

use App\Services\BaseService;
use App\Http\Requests\Cart\StoreRequest;
use App\Http\Requests\Cart\UpdateRequest;
use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use Throwable;
use Cart;

class CartService extends BaseService
{

    public function index()
    {
        $table = Cart::getContent();

        return $this->response(true, 'Berhasil mendapatkan data', $table);
    }

    public function store(StoreRequest $request)
    {
        try {
            $product_id = (empty($request->product_id)) ? null : trim(strip_tags($request->product_id));
            $product_name = (empty($request->product_name)) ? null : trim(strip_tags($request->product_name));
            $product_price = (empty($request->product_price)) ? null : trim(strip_tags($request->product_price));
            $qty = (empty($request->qty)) ? 0 : trim(strip_tags($request->qty));
            $image = (empty($request->image)) ? null : trim(strip_tags($request->image));

            $create = Cart::add([
                'id' => $product_id,
                'name' => $product_name,
                'price' => $product_price,
                'quantity' => $qty,
                'attributes' => array(
                    'image' => $image,
                )
            ]);

            return $this->response(true, 'Berhasil menambahkan cart',$create);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $qty = (empty($request->qty)) ? 0 : trim(strip_tags($request->qty));

            if($qty <= 0){
                Cart::remove($id);
            }
            else{
                Cart::update(
                    $id,
                    [
                        'quantity' => [
                            'relative' => false,
                            'value' => $qty
                        ],
                    ]
                );
            }

            return $this->response(true, 'Berhasil mengubah cart');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function delete($id)
    {
        try {
            Cart::remove($id);

            return $this->response(true, 'Berhasil menghapus cart');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function clear()
    {
        try {
            Cart::clear();

            return $this->response(true, 'Berhasil menghapus semua cart');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
    
}
