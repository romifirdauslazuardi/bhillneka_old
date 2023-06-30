<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ProductEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Product;
use App\Services\ProductService;
use App\Services\UserService;
use App\Services\BusinessService;
use Log;
use Auth;

class ProductController extends Controller
{
    protected $route;
    protected $view;
    protected $productService;
    protected $userService;
    protected $businessService;

    public function __construct()
    {
        $this->route = "dashboard.products.";
        $this->view = "dashboard.products.";
        $this->productService = new ProductService();
        $this->userService = new UserService();
        $this->businessService = new BusinessService();

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
        },['only' => ['index','show']]);
    }

    public function index(Request $request)
    {
        $response = $this->productService->index($request);

        $users = $this->userService->index(new Request(['role' => RoleEnum::AGEN]),false);
        $users = $users->data;

        $status = ProductEnum::status();
        $is_using_stock = ProductEnum::is_using_stock();

        $business = $this->businessService->index(new Request([]),false);
        $business = $business->data;

        $data = [
            'table' => $response->data,
            'users' => $users,
            'status' => $status,
            'is_using_stock' => $is_using_stock,
            'business' => $business,
        ];

        return view($this->view . 'index', $data);
    }

    public function create()
    {
        $status = ProductEnum::status();
        $is_using_stock = ProductEnum::is_using_stock();

        $mikrotik = ProductEnum::mikrotik();

        $data = [
            'status' => $status,
            'is_using_stock' => $is_using_stock,
            'mikrotik' => $mikrotik,
        ];

        return view($this->view . "create", $data);
    }

    public function show($id)
    {
        $result = $this->productService->show($id);
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
        $result = $this->productService->show($id);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route($this->route . 'index')->withInput();
        }
        $result = $result->data;

        $status = ProductEnum::status();
        $is_using_stock = ProductEnum::is_using_stock();

        $mikrotik = ProductEnum::mikrotik();

        $data = [
            'result' => $result,
            'status' => $status,
            'is_using_stock' => $is_using_stock,
            'mikrotik' => $mikrotik,
        ];

        return view($this->view . "edit", $data);
    }

    public function store(StoreRequest $request)
    {
        try {
            $response = $this->productService->store($request);
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
            $response = $this->productService->update($request, $id);
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
            $response = $this->productService->delete($id);
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

    public function qrcode($id)
    {
        $result = $this->productService->show($id);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route($this->route . 'index')->withInput();
        }
        $result = $result->data;

        $data = [
            'result' => $result
        ];

        return view($this->view . "qrcode", $data);
    }
}
