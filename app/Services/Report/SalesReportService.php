<?php

namespace App\Services\Report;

use App\Enums\OrderEnum;
use App\Services\BaseService;
use App\Services\OrderService;
use App\Models\Order;
use Illuminate\Http\Request;

/**
 * Class SalesReportService
 * @package App\Services
 */
class SalesReportService extends BaseService
{
    protected $order;
    protected $orderService;

    public function __construct()
    {
        $this->order = new Order();  
        $this->orderService = new OrderService(); 
    }

    public function index(Request $request)
    {
        $request->merge(['status' => OrderEnum::STATUS_SUCCESS]);

        $ordersWithoutPagination = $this->orderService->index($request,false);
        $ordersWithoutPagination = $ordersWithoutPagination->data;

        $ordersPagination = $this->orderService->index($request,true);
        $ordersPagination = $ordersPagination->data;

        $total = 0;
        foreach($ordersWithoutPagination as $index => $row){
            $total += $row->totalNeto();
        }

        $data = [
            'total' => $total,
            'ordersPagination' => $ordersPagination,
            'ordersWithoutPagination' => $ordersWithoutPagination,
        ];

        return $this->response(true, 'Berhasil mendapatkan data', $data);
    }
}
