<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\BusinessCategoryEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Http\Requests\MikrotikConfig\StoreRequest;
use App\Http\Requests\MikrotikConfig\UpdateRequest;
use App\Services\MikrotikConfigService;
use App\Services\UserService;
use App\Services\BusinessService;
use Log;
use Auth;

class MikrotikConfigController extends Controller
{
    protected $route;
    protected $view;
    protected $mikrotikConfigService;
    protected $userService;
    protected $businessService;

    public function __construct()
    {
        $this->route = "dashboard.mikrotik-configs.";
        $this->view = "dashboard.mikrotik-configs.";
        $this->mikrotikConfigService = new MikrotikConfigService();
        $this->userService = new UserService();
        $this->businessService = new BusinessService();

        $this->middleware(function ($request, $next) {
            if(empty(Auth::user()->business_id)){
                alert()->error('Gagal', "Bisnis page belum diaktifkan");
                return redirect()->route("dashboard.index");
            }
            return $next($request);
        },['only' => ['store','update','destroy','create','edit']]);

        $this->middleware(function ($request, $next) {
            if(Auth::user()->hasRole([
                RoleEnum::AGEN,
                RoleEnum::ADMIN_AGEN]) 
            && empty(Auth::user()->business_id)){
                alert()->error('Gagal', "Bisnis page belum diaktifkan");
                return redirect()->route("dashboard.index");
            }

            if(Auth::user()->hasRole([
                RoleEnum::AGEN,
                RoleEnum::ADMIN_AGEN]) 
            && !empty(Auth::user()->business_id) && Auth::user()->business->category->name != BusinessCategoryEnum::MIKROTIK){
                return redirect()->route("dashboard.index");
            }

            return $next($request);
        },['only' => ['index','show']]);
        
    }

    public function index(Request $request)
    {
        $response = $this->mikrotikConfigService->index($request);

        $users = $this->userService->index(new Request(['role' => RoleEnum::AGEN]),false);
        $users = $users->data;

        $business = $this->businessService->index(new Request([]),false);
        $business = $business->data;

        $data = [
            'table' => $response->data,
            'users' => $users,
            'business' => $business,
        ];

        return view($this->view . 'index', $data);
    }

    public function create()
    {
        return view($this->view . "create");
    }

    public function show($id)
    {
        $result = $this->mikrotikConfigService->show($id);
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
        $result = $this->mikrotikConfigService->show($id);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route($this->route . 'index')->withInput();
        }
        $result = $result->data;

        $data = [
            'result' => $result
        ];

        return view($this->view . "edit", $data);
    }

    public function store(StoreRequest $request)
    {
        try {
            $response = $this->mikrotikConfigService->store($request);
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
            $response = $this->mikrotikConfigService->update($request, $id);
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
            $response = $this->mikrotikConfigService->delete($id);
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
}
