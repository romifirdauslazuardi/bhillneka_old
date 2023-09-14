<?php

namespace App\Http\Controllers\LandingPage;

use Cart;
use App\Traits\HasSeo;
use App\Enums\OrderEnum;
use App\Enums\ProductEnum;
use App\Enums\ProviderEnum;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\TableService;
use App\Models\ProductCategory;
use App\Services\ProductService;
use App\Services\BusinessService;
use App\Services\ProviderService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Cart\StoreRequest;
use App\Http\Requests\Cart\UpdateRequest;

class ShopController extends Controller
{
    use HasSeo;

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

    public function index($business_slug,Request $request){

        $table_slug = $request->table;

        $business = $this->businessService->showBySlug($business_slug);
        if(!$business->success){
            alert()->html("Gagal",$business->message, 'error');
            return redirect()->route("landing-page.home.index");
        }
        $business = $business->data;

        if(!empty($table_slug)){
            $table = $this->tableService->showByCode($table_slug);
            if(!$table->success){
                alert()->html("Gagal",$table->message, 'error');
                return redirect()->route("landing-page.home.index");
            }
            $table = $table->data;
        }
        else{
            $table = null;
        }

        $request->merge([
            'business_id' => $business->id,
            'status' => ProductEnum::STATUS_TRUE
        ]);

        $products = $this->productService->index($request,false,true);
        $products = $products->data;

        $providers = $this->providerService->index(new Request(['status' => ProviderEnum::STATUS_TRUE]),false);
        $providers = $providers->data;

        foreach($providers as $index => $row){
            if($row->type == ProviderEnum::TYPE_PAY_LATER){
                if(empty($business->user_pay_later->status)){
                    unset($providers[$index]);
                }
            }
        }

        $carts = $this->cartService->index();
        $carts = $carts->data;

        $fnb_type = OrderEnum::fnb_type();

        $this->seo(
            title: "Katalog",
        );

        $data = [
            'products' => $products,
            'business' => $business,
            'table' => $table,
            'providers' => $providers,
            'carts' => $carts,
            'fnb_type' => $fnb_type,
            'product_category'=>ProductCategory::where('business_id',$business->id)->get()
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
