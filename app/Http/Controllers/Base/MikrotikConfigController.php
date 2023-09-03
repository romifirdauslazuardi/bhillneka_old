<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Services\MikrotikConfigService;
use Auth;
use Log;

class MikrotikConfigController extends Controller
{
    protected $mikrotikConfigService;

    public function __construct()
    {
        $this->mikrotikConfigService = new MikrotikConfigService();
    }

    public function profilePppoe($mikrotik_id)
    {
        try {
            $response = $this->mikrotikConfigService->profilePppoe($mikrotik_id);
            if (!$response->success) {
                return ResponseHelper::apiResponse(false, $response->message, null, null, $response->code);
            }

            return ResponseHelper::apiResponse(true, $response->message, $response->data, null, $response->code);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return ResponseHelper::apiResponse(false, $th->getMessage(), null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function profileHotspot($mikrotik_id)
    {
        try {
            $response = $this->mikrotikConfigService->profileHotspot($mikrotik_id);
            if (!$response->success) {
                return ResponseHelper::apiResponse(false, $response->message, null, null, $response->code);
            }

            return ResponseHelper::apiResponse(true, $response->message, $response->data, null, $response->code);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return ResponseHelper::apiResponse(false, $th->getMessage(), null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function serverHotspot($mikrotik_id)
    {
        try {
            $response = $this->mikrotikConfigService->serverHotspot($mikrotik_id);
            if (!$response->success) {
                return ResponseHelper::apiResponse(false, $response->message, null, null, $response->code);
            }

            return ResponseHelper::apiResponse(true, $response->message, $response->data, null, $response->code);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return ResponseHelper::apiResponse(false, $th->getMessage(), null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function detailProfilePppoe($mikrotik_id, $name)
    {
        try {
            $response = $this->mikrotikConfigService->detailProfilePppoe($mikrotik_id, $name);
            if (!$response->success) {
                return ResponseHelper::apiResponse(false, $response->message, null, null, $response->code);
            }

            return ResponseHelper::apiResponse(true, $response->message, $response->data, null, $response->code);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return ResponseHelper::apiResponse(false, $th->getMessage(), null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
