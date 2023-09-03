<?php

namespace App\Http\Controllers\Base;

use App\Enums\ProductEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Services\ProductService;
use Auth;
use Log;

class ProductController extends Controller
{
    protected $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    public function index(Request $request)
    {
        try {
            $request->merge(['status' => ProductEnum::STATUS_TRUE]);
            $response = $this->productService->index($request,false);
            if (!$response->success) {
                return ResponseHelper::apiResponse(false, $response->message , null, null, $response->code);
            }

            return ResponseHelper::apiResponse(true, $response->message , $response->data , null, $response->code);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return ResponseHelper::apiResponse(false, $th->getMessage() , null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showByCode(Request $request)
    {
        try {
            $response = $this->productService->showByCode($request);
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
