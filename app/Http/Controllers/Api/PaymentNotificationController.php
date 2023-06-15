<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CallbackService;
use Illuminate\Http\Response;
use Log;
use DB;

class PaymentNotificationController extends Controller
{
    protected $callbackService;

    public function __construct()
    {
        $this->callbackService = new CallbackService();
    }

    public function notifications(Request $request)
    {
        try {
            $response = $this->callbackService->doku($request);
            return response($response->message, $response->code)->header('Content-Type', 'text/plain');
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());
            return response($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR)->header('Content-Type', 'text/plain');
        }
    }
}
