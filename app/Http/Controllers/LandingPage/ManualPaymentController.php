<?php

namespace App\Http\Controllers\LandingPage;

use App\Enums\OrderEnum;
use App\Enums\ProviderEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\ProofOrderRequest;
use App\Services\OrderService;
use Log;

class ManualPaymentController extends Controller
{
    protected $route;
    protected $view;
    protected $orderService;

    public function __construct()
    {
        $this->route = "landing-page.manual-payments.";
        $this->view = "landing-page.manual-payments.";
        $this->orderService = new OrderService();
    }

    public function index($code){
        $result = $this->orderService->showByCode($code);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route('landing-page.home.index')->withInput();
        }
        $result = $result->data;

        if($result->provider->type != ProviderEnum::TYPE_MANUAL_TRANSFER){
            alert()->error('Gagal', "Metode pembayaran tidak valid");
            return redirect()->route('landing-page.home.index')->withInput();
        }

        if($result->status != OrderEnum::STATUS_WAITING_PAYMENT){
            alert()->error('Gagal', "Status order tidak valid");
            return redirect()->route('landing-page.home.index')->withInput();
        }

        $data = [
            'result' => $result
        ];

        return view($this->view."index",$data);
    }

    public function proofOrder(ProofOrderRequest $request, $id)
    {
        try {
            $response = $this->orderService->proofOrder($request, $id);
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
