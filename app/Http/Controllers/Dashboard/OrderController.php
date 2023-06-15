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
use App\Helpers\ResponseHelper;
use App\Http\Requests\Order\ProofOrderRequest;
use Log;
use Excel;
use Illuminate\Support\Collection;

class OrderController extends Controller
{
    protected $route;
    protected $view;
    protected $userService;
    protected $providerService;
    protected $orderService;

    public function __construct()
    {
        $this->route = "dashboard.orders.";
        $this->view = "dashboard.orders.";
        $this->userService = new UserService();
        $this->providerService = new ProviderService();
        $this->orderService = new OrderService();
    }

    public function index(Request $request)
    {
        $response = $this->orderService->index($request);

        $users = $this->userService->index(new Request(['role' => RoleEnum::AGEN]),false);
        $users = $users->data;

        $providers = $this->providerService->index(new Request([]),false);
        $providers = $providers->data;

        $status = OrderEnum::status();

        $data = [
            'table' => $response->data,
            'users' => $users,
            'status' => $status,
            'providers' => $providers,
        ];

        return view($this->view . 'index', $data);
    }

    public function create()
    {
        $users = $this->userService->getUserAgen();
        $users = $users->data;

        $providers = $this->providerService->index(new Request(['status' => ProviderEnum::STATUS_TRUE]),false);
        $providers = $providers->data;

        $data = [
            'users' => $users,
            'providers' => $providers,
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

        $users = $this->userService->getUserAgen();
        $users = $users->data;

        $providers = $this->providerService->index(new Request(['status' => ProviderEnum::STATUS_TRUE]),false);
        $providers = $providers->data;

        $status = OrderEnum::status();

        $data = [
            'users' => $users,
            'result' => $result,
            'status' => $status,
            'providers' => $providers,
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

            return ResponseHelper::apiResponse(true, $response->message , $response->data , null, $response->code);
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
}
