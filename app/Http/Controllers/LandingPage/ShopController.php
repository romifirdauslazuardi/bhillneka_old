<?php

namespace App\Http\Controllers\LandingPage;

use App\Enums\OrderEnum;
use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Services\BusinessService;
use App\Services\TableService;
use App\Services\ProviderService;
use App\Enums\ProviderEnum;
use App\Enums\ProductEnum;
use App\Http\Requests\Cart\StoreRequest;
use App\Http\Requests\Cart\UpdateRequest;
use App\Services\CartService;
use Illuminate\Http\Request;
use Cart;

class ShopController extends Controller
{
    protected $route;
    protected $view;
    protected $productService;
    protected $businessService;
    protected $tableService;
    protected $providerService;
    protected $cartService;

    public function __construct()
    {
        $this->route = "landing-page.shops.";
        $this->view = "landing-page.shops.";
        $this->productService = new ProductService();
        $this->businessService = new BusinessService();
        $this->tableService = new TableService();
        $this->providerService = new ProviderService();
        $this->cartService = new CartService();
    }

    public function index($business_id,$table_id,Request $request){

        $request->merge([
            'business_id' => $business_id,
            'status' => ProductEnum::STATUS_TRUE
        ]);

        $products = $this->productService->index($request,false);
        $products = $products->data;

        $business = $this->businessService->show($business_id);
        $business = $business->data;

        $table = $this->tableService->show($table_id);
        $table = $table->data;

        $providers = $this->providerService->index(new Request(['status' => ProviderEnum::STATUS_TRUE]),false);
        $providers = $providers->data;

        $carts = $this->cartService->index();
        $carts = $carts->data;

        $fnb_type = OrderEnum::fnb_type();

        $data = [
            'products' => $products,
            'business' => $business,
            'table' => $table,
            'providers' => $providers,
            'carts' => $carts,
            'fnb_type' => $fnb_type,
        ];

        return view($this->view."index",$data);
    }

    public function addToCart(StoreRequest $request)
    {
        try {
            $response = $this->cartService->store($request);
            if (!$response->success) {
                alert()->html("Gagal",$response->message, 'error');
                return redirect()->back()->with("error",$response->message)->withInput();
            }
            
            alert()->html('Berhasil',$response->message,'success'); 
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());
            alert()->html("Gagal",$th->getMessage(), 'error');
            return redirect()->back()->with("error",$th->getMessage())->withInput();
        }
    }

    public function updateCart(UpdateRequest $request,$id)
    {
        try {
            $response = $this->cartService->update($request,$id);
            if (!$response->success) {
                alert()->html("Gagal",$response->message, 'error');
                return redirect()->back()->with("error",$response->message)->withInput();
            }
            
            alert()->html('Berhasil',$response->message,'success'); 
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());
            alert()->html("Gagal",$th->getMessage(), 'error');
            return redirect()->back()->with("error",$th->getMessage())->withInput();
        }
    }

    public function deleteCart($id)
    {
        try {
            $response = $this->cartService->delete($id);
            if (!$response->success) {
                alert()->html("Gagal",$response->message, 'error');
                return redirect()->back()->with("error",$response->message)->withInput();
            }
            
            alert()->html('Berhasil',$response->message,'success'); 
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());
            alert()->html("Gagal",$th->getMessage(), 'error');
            return redirect()->back()->with("error",$th->getMessage())->withInput();
        }
    }

    public function clearCart()
    {
        try {
            $response = $this->cartService->clear();
            if (!$response->success) {
                alert()->html("Gagal",$response->message, 'error');
                return redirect()->back()->with("error",$response->message)->withInput();
            }
            
            alert()->html('Berhasil',$response->message,'success'); 
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());
            alert()->html("Gagal",$th->getMessage(), 'error');
            return redirect()->back()->with("error",$th->getMessage())->withInput();
        }
    }
}
