<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\CostAccountingEnum;
use App\Enums\RoleEnum;
use App\Exports\CostAccountingExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use App\Helpers\ResponseHelper;
use App\Http\Requests\CostAccounting\ImportRequest;
use App\Http\Requests\CostAccounting\StoreRequest;
use App\Http\Requests\CostAccounting\UpdateRequest;
use App\Services\CostAccountingService;
use App\Services\UserService;
use Log;
use Auth;
use Excel;

class CostAccountingController extends Controller
{
    protected $route;
    protected $view;
    protected $costAccountingService;
    protected $userService;

    public function __construct()
    {
        $this->route = "dashboard.cost-accountings.";
        $this->view = "dashboard.cost-accountings.";
        $this->costAccountingService = new CostAccountingService();
        $this->userService = new UserService();

        $this->middleware(function ($request, $next) {
            if(empty(Auth::user()->business_id)){
                alert()->error('Gagal', "Bisnis page belum diaktifkan");
                return redirect()->route("dashboard.index");
            }
            return $next($request);
        },['only' => ['create','edit','store','destroy']]);

        $this->middleware(function ($request, $next) {
            if(Auth::user()->hasRole([
                RoleEnum::AGEN,
                RoleEnum::ADMIN_AGEN]) 
            && empty(Auth::user()->business_id)){
                alert()->error('Gagal', "Bisnis page belum diaktifkan");
                return redirect()->route("dashboard.index");
            }
            return $next($request);
        },['only' => ['index','show','create','store']]);
    }

    public function index(Request $request)
    {
        $response = $this->costAccountingService->index($request);

        $users = $this->userService->index(new Request(['role' => RoleEnum::AGEN]),false);
        $users = $users->data;

        $type = CostAccountingEnum::type();

        $tableWithoutPagination = $this->costAccountingService->index($request,false);
        $tableWithoutPagination = $tableWithoutPagination->data;

        $totalIn = 0;
        $totalOut = 0;
        $totalIncome = 0;

        foreach($tableWithoutPagination as $index => $row){

            if($row->type == CostAccountingEnum::TYPE_PEMASUKAN){
                $totalIn += $row->nominal;
            }
            else if($row->type == CostAccountingEnum::TYPE_PENGELUARAN){
                $totalOut += $row->nominal;
            }
        }

        $totalIncome = $totalIn - $totalOut;

        $data = [
            'table' => $response->data,
            'users' => $users,
            'type' => $type,
            'totalIn' => $totalIn,
            'totalOut' => $totalOut,
            'totalIncome' => $totalIncome,
        ];

        return view($this->view . 'index', $data);
    }

    public function create()
    {
        $type = CostAccountingEnum::type();

        $users = $this->userService->getUserCustomer(new Request([]));
        $users = $users->data;

        $data = [
            'type' => $type,
            'users' => $users,
        ];

        return view($this->view . "create",$data);
    }

    public function show($id)
    {
        $result = $this->costAccountingService->show($id);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route($this->route . 'index')->withInput();
        }
        $result = $result->data;

        $data = [
            'result' => $result
        ];

        return view($this->view . "show", $data);
    }

    public function edit($id)
    {
        $result = $this->costAccountingService->show($id);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route($this->route . 'index')->withInput();
        }
        $result = $result->data;

        $users = $this->userService->getUserCustomer(new Request([]));
        $users = $users->data;

        $type = CostAccountingEnum::type();

        $data = [
            'result' => $result,
            'users' => $users,
            'type' => $type,
        ];

        return view($this->view . "edit", $data);
    }

    public function store(StoreRequest $request)
    {
        try {
            $response = $this->costAccountingService->store($request);
            if (!$response->success) {
                return ResponseHelper::apiResponse(false, $response->message , null, null, $response->code);
            }

            return ResponseHelper::apiResponse(true, $response->message , $response->data , null, $response->code);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return ResponseHelper::apiResponse(false, $th->getMessage() , null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $response = $this->costAccountingService->update($request, $id);
            if (!$response->success) {
                return ResponseHelper::apiResponse(false, $response->message , null, null, $response->code);
            }
            
            return ResponseHelper::apiResponse(true, $response->message , $response->data , null, $response->code);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return ResponseHelper::apiResponse(false, $th->getMessage() , null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $response = $this->costAccountingService->delete($id);
            if (!$response->success) {
                alert()->error('Gagal', $response->message);
                return redirect()->route($this->route . 'index')->withInput();
            }

            alert()->html('Berhasil', $response->message, 'success');
            return redirect()->route($this->route . 'index');
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            alert()->error('Gagal', $th->getMessage());
            return redirect()->route($this->route . 'index')->withInput();
        }
    }

    public function exportExcel(Request $request){
        try {
            $table = $this->costAccountingService->index($request,false);
            $table = $table->data;

            $totalIn = 0;
            $totalOut = 0;
            $totalIncome = 0;

            $collection = new Collection();
            foreach($table as $index => $row){
                if($row->type == CostAccountingEnum::TYPE_PEMASUKAN){
                    $totalIn += $row->nominal;
                }
                else if($row->type == CostAccountingEnum::TYPE_PENGELUARAN){
                    $totalOut += $row->nominal;
                }
                $collection->push([
                    $index+1,
                    date("d-m-Y",strtotime($row->date)),
                    $row->name,
                    $row->description,
                    ($row->type == CostAccountingEnum::TYPE_PEMASUKAN) ? $row->nominal : null,
                    ($row->type == CostAccountingEnum::TYPE_PENGELUARAN) ? $row->nominal : null,
                    date('d-m-Y H:i:s',strtotime($row->created_at))
                ]);
            }

            $totalIncome = $totalIn - $totalOut;

            $collection->push([
                null,
                null,
                null,
                null,
                null,
                null,
                null,
            ]);

            $collection->push([
                "Total Pemasukan",
                $totalIn,
                null,
                null,
                null,
                null,
                null,
            ]);

            $collection->push([
                "Total Pengeluaran",
                $totalOut,
                null,
                null,
                null,
                null,
                null,
            ]);

            $collection->push([
                "Total Pendapatan",
                $totalIncome,
                null,
                null,
                null,
                null,
                null,
            ]);


            return Excel::download(new CostAccountingExport($collection), 'cost-accountings-'.time().'.xlsx');
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());
            alert()->error('Gagal', $th->getMessage());
            return redirect()->route($this->route . 'index')->withInput();
        }
    }

    public function importExcel(ImportRequest $request)
    {
        try {
            $response = $this->costAccountingService->importExcel($request);
            if (!$response->success) {
                return ResponseHelper::apiResponse(false, $response->message , null, null, $response->code);
            }

            return ResponseHelper::apiResponse(true, $response->message , $response->data , null, $response->code);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return ResponseHelper::apiResponse(false, $th->getMessage() , null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
}
