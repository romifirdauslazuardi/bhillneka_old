<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Services\OrderService;
use Auth;
use Illuminate\Support\Collection;
use Log;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    public function index(Request $request)
    {
        try {
            $response = $this->orderService->index($request);
            if (!$response->success) {
                return ResponseHelper::apiResponse(false, $response->message , null, null, $response->code);
            }
            
            $collection = new Collection();
            foreach($response->data as $index => $row){
                $collection->push([
                    'code' => $row->code,
                    'total' => $row->totalNeto(),
                    'status' => [
                        'class' => $row->status()->class ?? null,
                        'msg' => $row->status()->msg ?? null
                    ]
                ]);
            }

            return ResponseHelper::apiResponse(true, $response->message , $collection , null, $response->code);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return ResponseHelper::apiResponse(false, $th->getMessage() , null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
