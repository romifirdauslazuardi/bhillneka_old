<?php

namespace App\Http\Controllers\Dashboard\Report;

use App\Http\Controllers\Controller;
use App\Services\Report\SalesReportService;
use Illuminate\Http\Request;
use App\Exports\Report\SalesReportExport;
use Illuminate\Support\Collection;
use Excel;
use Log;

class SalesReportController extends Controller
{
    protected $route;
    protected $view;
    protected $salesReportService;

    public function __construct()
    {
        $this->route = "dashboard.reports.sales.";
        $this->view = "dashboard.reports.sales.";
        $this->salesReportService = new SalesReportService();
    }

    public function index(Request $request)
    {
        $response = $this->salesReportService->index($request);
        $response = $response->data;

        $data = [
            'total' => $response["total"],
            'table' => $response["ordersPagination"],
        ];

        return view($this->view . 'index', $data);
    }

    public function exportExcel(Request $request){
        try {
            $response = $this->salesReportService->index($request);
            $response = $response->data;

            $orders = $response["ordersWithoutPagination"];
            $total = $response["total"];

            $collection = new Collection();
            foreach($orders as $index => $row){
                $collection->push([
                    $index+1,
                    $row->code,
                    $row->totalNeto(),
                    $row->status()->msg ?? null,
                    date('d-m-Y H:i:s',strtotime($row->created_at))
                ]);
            }

            $collection->push([
                null,
                null,
                null,
                null,
                null,
            ]);

            $collection->push([
                null,
                "Total",
                $total,
                null,
                null
            ]);

            return Excel::download(new SalesReportExport($collection), 'sales-report-'.time().'.xlsx');
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());
            alert()->error('Gagal', $th->getMessage());
            return redirect()->route($this->route . 'index')->withInput();
        }
    }


}
