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
class IncomeReportService extends BaseService
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
        $total_owner = 0;
        $total_agen = 0;
        $total_doku_fee = 0;
        foreach($ordersWithoutPagination as $index => $row){
            $total_owner += $row->incomeOwnerNeto();
            $total_agen += $row->incomeAgenNeto();
            $total += $row->totalNeto();
            $total_doku_fee += $row->doku_fee;
        }

        $data = [
            'total' => $total,
            'total_owner' => $total_owner,
            'total_agen' => $total_agen,
            'total_doku_fee' => $total_doku_fee,
            'ordersPagination' => $ordersPagination,
            'ordersWithoutPagination' => $ordersWithoutPagination,
        ];

        return $this->response(true, 'Berhasil mendapatkan data', $data);
    }
}
