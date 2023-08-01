<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected $route;
    protected $view;
    protected $dashboardService;

    public function __construct()
    {
        $this->route = "dashboard.index";
        $this->view = "dashboard.dashboard";
        $this->dashboardService = new DashboardService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $total_product = $this->dashboardService->totalProduct();
        $total_product = $total_product->data;

        $total_income_owner = $this->dashboardService->totalIncomeOwnerNeto();
        $total_income_owner = $total_income_owner->data;

        $total_income_agen = $this->dashboardService->totalIncomeAgenNeto();
        $total_income_agen = $total_income_agen->data;

        $chart_income_agen = $this->dashboardService->chartIncomeAgenNeto();
        $chart_income_agen = $chart_income_agen->data;

        $chart_income_owner = $this->dashboardService->chartIncomeOwnerNeto();
        $chart_income_owner = $chart_income_owner->data;

        $orders = $this->dashboardService->orderLatest();
        $orders = $orders->data;
        
        $data = [
            'total_product' => $total_product,
            'total_income_agen' => $total_income_agen,
            'total_income_owner' => $total_income_owner,
            'chart_income_agen' => $chart_income_agen,
            'chart_income_owner' => $chart_income_owner,
            'orders' => $orders,
        ];

        return view($this->view,$data);
    }
}
