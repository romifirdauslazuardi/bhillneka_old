<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Services\BusinessService;
use Auth;
use Log;

class BusinessController extends Controller
{
    protected $businessService;

    public function __construct()
    {
        $this->businessService = new BusinessService();
    }

    public function index(Request $request)
    {
        try {
            $response = $this->businessService->index($request,false);
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
