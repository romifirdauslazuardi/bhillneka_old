<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ProductEnum;
use App\Enums\ProductStockEnum;
use App\Enums\RoleEnum;
use App\Exports\ProductStockExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use App\Helpers\ResponseHelper;
use App\Helpers\SettingHelper;
use App\Http\Requests\ProductStock\StoreRequest;
use App\Http\Requests\ProductStock\UpdateRequest;
use App\Services\ProductStockService;
use App\Services\ProductService;
use App\Services\UserService;
use Log;
use Auth;
use Excel;

class ProductStockController extends Controller
{
    protected $route;
    protected $view;
    protected $productStockService;
    protected $productService;
    protected $userService;

    public function __construct()
    {
        $this->route = "dashboard.product-stocks.";
        $this->view = "dashboard.product-stocks.";
        $this->productStockService = new ProductStockService();
        $this->productService = new ProductService();
        $this->userService = new UserService();

        $this->middleware(function ($request, $next) {
            if(empty(Auth::user()->business_id)){
                if($request->wantsJson()){
                    return ResponseHelper::apiResponse(false, "Bisnis page belum diaktifkan");
                }
                alert()->error('Gagal', "Bisnis page belum diaktifkan");
                return redirect()->route("dashboard.index");
            }
            return $next($request);
        },['only' => ['create','store','destroy']]);

        $this->middleware(function ($request, $next) {
            if(Auth::user()->hasRole([
                RoleEnum::AGEN,
                RoleEnum::ADMIN_AGEN]) 
            && empty(Auth::user()->business_id)){
                if($request->wantsJson()){
                    return ResponseHelper::apiResponse(false, "Bisnis page belum diaktifkan");
                }
                alert()->error('Gagal', "Bisnis page belum diaktifkan");
                return redirect()->route("dashboard.index");
            }
            return $next($request);
        },['only' => ['index','show','create','store']]);

        $this->middleware(function ($request, $next) {
            if(SettingHelper::hasBankActive()==false){
                if($request->wantsJson()){
                    return ResponseHelper::apiResponse(false, "Tidak ada rekening bank anda yang sudah diverifikasi oleh owner");
                }
                alert()->error('Gagal', "Tidak ada rekening bank anda yang sudah diverifikasi oleh owner");
                return redirect()->route("dashboard.index");
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $request->merge(["is_using_stock" => ProductEnum::IS_USING_STOCK_TRUE]);
        $response = $this->productService->index($request);
        $response = $response->data;

        $users = $this->userService->index(new Request(['role' => RoleEnum::AGEN]),false);
        $users = $users->data;

        $type = ProductStockEnum::type();

        $data = [
            'table' => $response,
            'users' => $users,
            'type' => $type,
        ];

        return view($this->view . 'index', $data);
    }

    public function create()
    {
        $type = ProductStockEnum::type();

        $products = $this->productService->index(new Request(["is_using_stock" => ProductEnum::IS_USING_STOCK_TRUE]),false);
        $products = $products->data;

        $data = [
            'type' => $type,
            'products' => $products,
        ];

        return view($this->view . "create",$data);
    }

    public function show($id)
    {
        $result = $this->productService->show($id);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route($this->route . 'index')->withInput();
        }
        $result = $result->data;

        $type = ProductStockEnum::type();

        $data = [
            'result' => $result,
            'type' => $type,
        ];

        return view($this->view . "show", $data);
    }

    public function store(StoreRequest $request)
    {
        try {
            $response = $this->productStockService->store($request);
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
            $response = $this->productStockService->update($request, $id);
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
            $response = $this->productStockService->delete($id);
            if (!$response->success) {
                alert()->error('Gagal', $response->message);
                return redirect()->back()->withInput();
            }

            alert()->html('Berhasil', $response->message, 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            alert()->error('Gagal', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function exportExcel(Request $request){
        try {
            $table = $this->productService->index($request,false);
            $table = $table->data;

            $collection = new Collection();
            foreach($table as $index => $row){
                $collection->push([
                    $index+1,
                    $row->code,
                    $row->name,
                    $row->stock,
                    $row->business->category->name ?? null,
                    $row->business->name ?? null,
                    $row->business->user->name ?? null,
                    $row->status()->msg ?? null,
                ]);
            }

            return Excel::download(new ProductStockExport($collection), 'inventoris-'.time().'.xlsx');
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());
            alert()->error('Gagal', $th->getMessage());
            return redirect()->route($this->route . 'index')->withInput();
        }
    }
    
}
