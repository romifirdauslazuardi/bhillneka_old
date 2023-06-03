<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Http\Requests\ProductStock\StoreRequest;
use App\Http\Requests\ProductStock\UpdateRequest;
use App\Services\ProductStockService;
use Log;

class ProductStockController extends Controller
{
    protected $route;
    protected $view;
    protected $productStockService;

    public function __construct()
    {
        $this->route = "dashboard.products.";
        $this->view = "dashboard.products.";
        $this->productStockService = new ProductStockService();
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
}
