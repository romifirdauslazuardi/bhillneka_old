<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected $route;
    protected $view;
    protected $orderService;

    public function __construct()
    {
        $this->route = "landing-page.orders.";
        $this->view = "landing-page.orders.";
        $this->orderService = new OrderService();
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

            $data = [
                'result' => $result
            ];
        }

        return view($this->view."index",$data);
    }
}
