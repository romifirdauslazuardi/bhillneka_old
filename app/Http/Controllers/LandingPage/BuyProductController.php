<?php

namespace App\Http\Controllers\LandingPage;

use App\Enums\ProductEnum;
use App\Enums\ProviderEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreRequest;
use App\Services\ProductService;
use App\Services\OrderService;
use App\Services\ProviderService;
use App\Services\CartService;
use Illuminate\Http\Request;
use App\Traits\HasSeo;
use Log;

class BuyProductController extends Controller
{
    use HasSeo;

    protected $route;
    protected $view;
    protected $productService;
    protected $orderService;
    protected $providerService;
    protected $cartService;

    public function __construct()
    {
        $this->route = "landing-page.buy-products.";
        $this->view = "landing-page.buy-products.";
        $this->productService = new ProductService();
        $this->orderService = new OrderService();
        $this->providerService = new ProviderService();
        $this->cartService = new CartService(); 
    }

    public function index($slug){
        $result = $this->productService->showActiveBySlug($slug);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route('landing-page.home.index')->withInput();
        }
        $result = $result->data;

        if($result->mikrotik == ProductEnum::MIKROTIK_PPPOE){
            alert()->error('Gagal', "Fitur ini tidak berlaku pada produk mikrotik PPPOE");
            return redirect()->route('landing-page.home.index')->withInput();
        }

        $providers = $this->providerService->index(new Request(['status' => ProviderEnum::STATUS_TRUE]),false);
        $providers = $providers->data;

        $this->seo(
            title: $result->name,
            description: $result->description,
            keywords: $result->name,
            url: null,
            image: (!empty($result->image)) ? asset($result->image) : null,
        );

        $data = [
            'result' => $result,
            'providers' => $providers,
        ];

        return view($this->view."index",$data);
    }

    public function store(StoreRequest $request)
    {
        try {
            $response = $this->orderService->store($request);
            if (!$response->success) {
                alert()->html("Gagal",$response->message, 'error');
                return redirect()->back()->with("error",$response->message)->withInput();
            }

            $this->cartService->clear();
            
            if($response->data->provider->type == ProviderEnum::TYPE_DOKU){
                return redirect($response->data->payment_url);
            }
            else if($response->data->provider->type == ProviderEnum::TYPE_MANUAL_TRANSFER){
                return redirect($response->data->payment_url);
                alert()->html('Berhasil',$response->message,'success'); 
                return redirect()->route("landing-page.manual-payments.index",$response->data->code);
            }
            else{
                return redirect()->route("landing-page.orders.index",["code" => $response->data->code]);
            }
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());
            alert()->html("Gagal",$th->getMessage(), 'error');
            return redirect()->back()->with("error",$th->getMessage())->withInput();
        }
    }
}
