<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\OrderEnum;
use App\Enums\ProviderEnum;
use App\Enums\RoleEnum;
use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\Order\StoreRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Services\UserService;
use App\Services\ProviderService;
use App\Services\OrderService;
use App\Services\BusinessService;
use App\Services\MikrotikConfigService;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Order\ProofOrderRequest;
use App\Http\Requests\Order\UpdateProgressRequest;
use App\Http\Requests\Order\UpdateStatusRequest;
use Illuminate\Support\Collection;
use Log;
use Excel;
use Auth;

class OrderController extends Controller
{
    protected $route;
    protected $view;
    protected $userService;
    protected $providerService;
    protected $orderService;
    protected $businessService;
    protected $mikrotikConfigService;

    public function __construct()
    {
        $this->route = "dashboard.orders.";
        $this->view = "dashboard.orders.";
        $this->userService = new UserService();
        $this->providerService = new ProviderService();
        $this->orderService = new OrderService();
        $this->businessService = new BusinessService();
        $this->mikrotikConfigService = new MikrotikConfigService();

        $this->middleware(function ($request, $next) {
            if(empty(Auth::user()->business_id)){
                alert()->error('Gagal', "Bisnis page belum diaktifkan");
                return redirect()->route("dashboard.index");
            }
            return $next($request);
        },['only' => 'create','edit','store','update','destroy']);

        $this->middleware(function ($request, $next) {
            if(Auth::user()->hasRole([
                RoleEnum::AGEN,
                RoleEnum::ADMIN_AGEN]) 
            && empty(Auth::user()->business_id)){
                alert()->error('Gagal', "Bisnis page belum diaktifkan");
                return redirect()->route("dashboard.index");
            }
            return $next($request);
        },['only' => ['index','show']]);
    }

    public function index(Request $request)
    {
        $response = $this->orderService->index($request);

        $users = $this->userService->index(new Request(['role' => RoleEnum::AGEN]),false);
        $users = $users->data;

        $providers = $this->providerService->index(new Request([]),false);
        $providers = $providers->data;

        $business = $this->businessService->index(new Request([]),false);
        $business = $business->data;

        $status = OrderEnum::status();

        $progress = OrderEnum::progress();

        $data = [
            'table' => $response->data,
            'users' => $users,
            'status' => $status,
            'progress' => $progress,
            'providers' => $providers,
        ];

        return view($this->view . 'index', $data);
    }

    public function create()
    {
        $providers = $this->providerService->index(new Request(['status' => ProviderEnum::STATUS_TRUE]),false);
        $providers = $providers->data;

        foreach($providers as $index => $row){
            if($row->type == ProviderEnum::TYPE_PAY_LATER){
                unset($providers[$index]);
            }
        }

        $type = OrderEnum::type();

        $fnb_type = OrderEnum::fnb_type();

        $data = [
            'providers' => $providers,
            'type' => $type,
            'fnb_type' => $fnb_type,
        ];

        return view($this->view."create",$data);
    }

    public function show($id)
    {
        $result = $this->orderService->show($id);
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
        $result = $this->orderService->show($id);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route($this->route . 'index')->withInput();
        }
        $result = $result->data;

        $providers = $this->providerService->index(new Request(['status' => ProviderEnum::STATUS_TRUE]),false);
        $providers = $providers->data;

        $status = OrderEnum::status();

        $progress = OrderEnum::progress();

        $type = OrderEnum::type();

        $fnb_type = OrderEnum::fnb_type();

        $serverHotspot = $this->mikrotikConfigService->serverHotspot();
        $serverHotspot = $serverHotspot->data;

        $profileHotspot = $this->mikrotikConfigService->profileHotspot();
        $profileHotspot = $profileHotspot->data;

        $profilePppoe = $this->mikrotikConfigService->profilePppoe();
        $profilePppoe = $profilePppoe->data;

        $data = [
            'result' => $result,
            'status' => $status,
            'progress' => $progress,
            'type' => $type,
            'providers' => $providers,
            'fnb_type' => $fnb_type,
            'serverHotspot' => $serverHotspot,
            'profileHotspot' => $profileHotspot,
            'profilePppoe' => $profilePppoe,
        ];

        return view($this->view . "edit", $data);
    }

    public function store(StoreRequest $request)
    {
        try {
            $response = $this->orderService->store($request);
            if (!$response->success) {
                return ResponseHelper::apiResponse(false, $response->message , null, null, $response->code);
            }

            return ResponseHelper::apiResponse(false, $response->message , $response->data , null, $response->code);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return ResponseHelper::apiResponse(false, $th->getMessage() , null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $response = $this->orderService->update($request, $id);
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
            $response = $this->orderService->delete($id);
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

    public function proofOrder(ProofOrderRequest $request, $id)
    {
        try {
            $response = $this->orderService->proofOrder($request, $id);
            if (!$response->success) {
                return ResponseHelper::apiResponse(false, $response->message , null, null, $response->code);
            }
            
            return ResponseHelper::apiResponse(true, $response->message , $response->data , null, $response->code);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return ResponseHelper::apiResponse(false, $th->getMessage() , null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateProgress(UpdateProgressRequest $request, $id)
    {
        try {
            $response = $this->orderService->updateProgress($request, $id);
            if (!$response->success) {
                return ResponseHelper::apiResponse(false, $response->message , null, null, $response->code);
            }
            
            return ResponseHelper::apiResponse(true, $response->message , $response->data , null, $response->code);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return ResponseHelper::apiResponse(false, $th->getMessage() , null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateStatus(UpdateStatusRequest $request, $id)
    {
        try {
            $response = $this->orderService->updateStatus($request, $id);
            if (!$response->success) {
                return ResponseHelper::apiResponse(false, $response->message , null, null, $response->code);
            }
            
            return ResponseHelper::apiResponse(true, $response->message , $response->data , null, $response->code);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return ResponseHelper::apiResponse(false, $th->getMessage() , null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function exportExcel(Request $request){
        try {
            $orders = $this->orderService->index($request,false);
            $orders = $orders->data;

            $total = 0;
            $collection = new Collection();
            foreach($orders as $index => $row){
                $collection->push([
                    $index+1,
                    $row->code,
                    $row->user->name ?? null,
                    $row->cutomer->name ?? null,
                    $row->totalNeto(),
                    $row->provider->name ?? null,
                    $row->status()->msg ?? null,
                    $row->progress()->msg ?? null,
                    date('d-m-Y H:i:s',strtotime($row->created_at))
                ]);

                $total += $row->totalNeto();
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
                null,
                "Total",
                $total,
                null,
                null,
                null,
            ]);

            return Excel::download(new OrderExport($collection), 'orders-'.time().'.xlsx');
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());
            alert()->error('Gagal', $th->getMessage());
            return redirect()->route($this->route . 'index')->withInput();
        }
    }

    public function print($id)
    {
        $result = $this->orderService->show($id);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route($this->route . 'index')->withInput();
        }
        $result = $result->data;

        $data = [
            'result' => $result
        ];

        return view($this->view . "print", $data);
    }
}
