<?php

namespace App\Http\Controllers\Dashboard\Report;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Services\Report\IncomeReportService;
use Illuminate\Http\Request;
use App\Exports\Report\IncomeReportExport;
use App\Helpers\ResponseHelper;
use App\Helpers\SettingHelper;
use Illuminate\Support\Collection;
use Excel;
use Illuminate\Support\Facades\Auth;
use Log;

class IncomeReportController extends Controller
{
    protected $route;
    protected $view;
    protected $incomeReportService;

    public function __construct()
    {
        $this->route = "dashboard.reports.incomes.";
        $this->view = "dashboard.reports.incomes.";
        $this->incomeReportService = new IncomeReportService();

        $this->middleware(function ($request, $next) {
            if(SettingHelper::hasBankActive()==false){
                if($request->wantsJson()){
                    return ResponseHelper::apiResponse(false, "Tidak ada rekening bank yang sudah diverifikasi owner");
                }
                alert()->error('Gagal', "Tidak ada rekening bank anda yang sudah diverifikasi oleh owner");
                return redirect()->route("dashboard.index");
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $response = $this->incomeReportService->index($request);
        $response = $response->data;

        $data = [
            'total_owner' => $response["total_owner"],
            'total_agen' => $response["total_agen"],
            'table' => $response["ordersPagination"],
        ];

        return view($this->view . 'index', $data);
    }

    public function exportExcel(Request $request){
        try {
            $response = $this->incomeReportService->index($request);
            $response = $response->data;

            $orders = $response["ordersWithoutPagination"];
            $total = $response["total"];
            $total_owner = $response["total_owner"];
            $total_agen = $response["total_agen"];

            $collection = new Collection();
            foreach($orders as $index => $row){

                $customer = $row->customer->name ?? null;

                if(empty($row->customer_id)){
                    $customer = $row->customer_name;
                }

                $pushData = [];

                $pushData[] = $index+1;
                $pushData[] = $row->code;
                $pushData[] = $customer;
                $pushData[] = $row->incomeAgenNeto();
                $pushData[] = $row->incomeOwnerNeto();
                $pushData[] = $row->totalNeto();
                $pushData[] = $row->progress()->msg ?? null;
                $pushData[] = $row->status()->msg ?? null;
                $pushData[] = date('d-m-Y H:i:s',strtotime($row->created_at));

                $collection->push($pushData);
            }

            $collection->push([
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
            ]);

            $collection->push([
                null,
                null,
                "Total",
                $total_agen,
                $total_owner,
                $total,
                null,
                null,
                null,
            ]);
            

            return Excel::download(new IncomeReportExport($collection), 'incomes-report-'.time().'.xlsx');
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());
            alert()->error('Gagal', $th->getMessage());
            return redirect()->route($this->route . 'index')->withInput();
        }
    }
}
