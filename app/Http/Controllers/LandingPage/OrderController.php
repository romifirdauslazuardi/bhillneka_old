<?php

namespace App\Http\Controllers\LandingPage;

use App\Enums\ProviderEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\UpdateProviderRequest;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Services\ProviderService;
use App\Traits\HasSeo;

class OrderController extends Controller
{
    use HasSeo;

    protected $route;
    protected $view;
    protected $orderService;
    protected $providerService;

    public function __construct()
    {
        $this->route = "landing-page.orders.";
        $this->view = "landing-page.orders.";
        $this->orderService = new OrderService();
        $this->providerService = new ProviderService();
    }

    public function index(Request $request){
        $code = $request->code;

        $data = [];

        if($code){
            $result = $this->orderService->showByCode($code);
            if (!$result->success) {
                alert()->error('Gagal', $result->message);
                return redirect()->route('landing-page.orders.index')->withInput();
            }
            $result = $result->data;

            $providers = $this->providerService->index(new Request(['status' => ProviderEnum::STATUS_TRUE]),false);
            $providers = $providers->data;

            foreach($providers as $index => $row){
                if($row->type == ProviderEnum::TYPE_PAY_LATER){
                    if(empty($result->business->user_pay_later->status)){
                        unset($providers[$index]);
                    }
                }
            }

            $this->seo(
                title: "Pembelian",
            );

            $data = [
                'result' => $result,
                'providers' => $providers,
            ];
        }

        return view($this->view."index",$data);
    }

    public function updateProvider(UpdateProviderRequest $request, $id)
    {
        try {
            $response = $this->orderService->updateProvider($request, $id);
            if (!$response->success) {
                alert()->html("Gagal",$response->message, 'error');
                return redirect()->back()->with("error",$response->message)->withInput();
            }
            
            alert()->html('Berhasil',$response->message,'success'); 
            return redirect()->route("landing-page.orders.index",["code" => $response->data->code]);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());
            alert()->html("Gagal",$th->getMessage(), 'error');
            return redirect()->back()->with("error",$th->getMessage())->withInput();
        }
    }
}
