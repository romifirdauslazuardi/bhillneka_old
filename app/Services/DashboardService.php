<?php

namespace App\Services;

use App\Enums\OrderEnum;
use App\Services\BaseService;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Enums\RoleEnum;
use App\Services\ProductService;
use Auth;

/**
 * Class DashboardService
 * @package App\Services
 */
class DashboardService extends BaseService
{
    protected $product;
    protected $productService;
    protected $order;

    public function __construct()
    {
        $this->product = new Product();
        $this->productService = new ProductService();
        $this->order = new Order();
    }

    public function totalProduct(){
        $products = $this->productService->index(new Request([]),false);
        $products = $products->data;

        return $this->response(true, 'Berhasil mendapatkan data', count($products));
    }

    public function totalSales(){
        $orders = $this->orderSuccess();

        $total = 0;
        foreach($orders as $index => $row){
            $total += $row->totalNeto();
        }

        return $this->response(true, 'Berhasil mendapatkan data', $total);
    }

    public function totalIncomeOwnerBruto(){
        $orders = $this->orderSuccess();

        $total = 0;
        foreach($orders as $index => $row){
            $total += $row->incomeOwnerBruto();
        }

        return $this->response(true, 'Berhasil mendapatkan data', $total);
    }

    public function totalIncomeAgen(){
        $orders = $this->orderSuccess();

        $total = 0;
        foreach($orders as $index => $row){
            $total += $row->incomeAgen();
        }

        return $this->response(true, 'Berhasil mendapatkan data', $total);
    }

    public function chartIncomeAgen(){
        $orders = $this->orderSuccess();

        $labels = [];
        $value = [];

        $newLabel = [];
        $newValue = [];

        foreach($orders as $index => $row){
            if($index == 0){
                $labels[date('d-m-Y',strtotime($row->created_at))] = date('d F Y',strtotime($row->created_at));
                $value[date('d-m-Y',strtotime($row->created_at))] = $row->incomeAgen();
            }
            else{
                if(!isset($labels[date('d-m-Y',strtotime($row->created_at))])){
                    $labels[date('d-m-Y',strtotime($row->created_at))] = date('d F Y',strtotime($row->created_at));
                    $value[date('d-m-Y',strtotime($row->created_at))] = $row->incomeAgen();
                }
                else{
                    $value[date('d-m-Y',strtotime($row->created_at))] += $row->incomeAgen();
                }
            }
        }

        foreach($labels as $index => $row){
            $newLabel[] = $row;
        }

        foreach($value as $index => $row){
            $newValue[] = $row;
        }

        $data = [
            'labels' => $newLabel,
            'value' => $newValue,
        ];

        return $this->response(true, 'Berhasil mendapatkan data', $data);
    }

    public function chartIncomeOwnerNeto(){
        $orders = $this->orderSuccess();

        $labels = [];
        $value = [];

        $newLabel = [];
        $newValue = [];

        foreach($orders as $index => $row){
            if($index == 0){
                $labels[date('d-m-Y',strtotime($row->created_at))] = date('d F Y',strtotime($row->created_at));
                $value[date('d-m-Y',strtotime($row->created_at))] = $row->incomeOwnerNeto();
            }
            else{
                if(!isset($labels[date('d-m-Y',strtotime($row->created_at))])){
                    $labels[date('d-m-Y',strtotime($row->created_at))] = date('d F Y',strtotime($row->created_at));
                    $value[date('d-m-Y',strtotime($row->created_at))] = $row->incomeOwnerNeto();
                }
                else{
                    $value[date('d-m-Y',strtotime($row->created_at))] += $row->incomeOwnerNeto();
                }
            }
        }

        foreach($labels as $index => $row){
            $newLabel[] = $row;
        }

        foreach($value as $index => $row){
            $newValue[] = $row;
        }

        $data = [
            'labels' => $newLabel,
            'value' => $newValue,
        ];

        return $this->response(true, 'Berhasil mendapatkan data', $data);
    }

    public function orderLatest(){
        $orders = $this->orderSuccess(true)->take(10);

        return $this->response(true, 'Berhasil mendapatkan data', $orders);
    }

    private function orderSuccess(bool $latest = false){
        $user_id = null;
        $customer_id = null;

        if(Auth::user()->hasRole([RoleEnum::AGEN])){
            $user_id = Auth::user()->id;
        }
        if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
            $user_id = Auth::user()->user_id;
        }
        if(Auth::user()->hasRole([RoleEnum::USER])){
            $customer_id = Auth::user()->id;
        }

        $orders = $this->order;
        if(!empty($user_id)){
            $orders = $orders->where("user_id",$user_id);
        }
        if(!empty($customer_id)){
            $orders = $orders->where("customer_id",$customer_id);
        }
        $orders = $orders->whereMonth('created_at',date("m"));
        $orders = $orders->whereYear('created_at',date("Y"));
        $orders = $orders->where("status",OrderEnum::STATUS_SUCCESS);
        if($latest == false){
            $orders = $orders->orderBy("created_at","ASC");
        }
        else{
            $orders = $orders->orderBy("created_at","DESC");
        }
        $orders = $orders->get();

        return $orders;
    }

}
