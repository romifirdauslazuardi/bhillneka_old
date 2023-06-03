<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Setting\SettingFeeRequest;
use App\Services\SettingFeeService;
use Log;
use Auth;

class SettingFeeController extends Controller
{
    protected $route;
    protected $view;
    protected $settingFeeService;

    public function __construct()
    {
        $this->route = "dashboard.settings.fee.";
        $this->view = "dashboard.settings.fee";
        $this->settingFeeService = new SettingFeeService();
    }

    public function index()
    {

        $result = $this->settingFeeService->index();
        $result = $result->data;

        $data = [
            'result' => $result
        ];

        return view($this->view, $data);
    }

    public function update(SettingFeeRequest $request)
    {
        try {
            $response = $this->settingFeeService->update($request);
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
