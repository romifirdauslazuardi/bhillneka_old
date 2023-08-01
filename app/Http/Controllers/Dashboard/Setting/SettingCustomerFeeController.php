<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Setting\SettingCustomerFee\StoreRequest;
use App\Http\Requests\Setting\SettingCustomerFee\UpdateRequest;
use App\Services\SettingCustomerFeeService;
use App\Enums\SettingCustomerFeeEnum;
use Log;

class SettingCustomerFeeController extends Controller
{
    protected $route;
    protected $view;
    protected $settingCustomerFeeService;

    public function __construct()
    {
        $this->route = "dashboard.settings.customer-fee.";
        $this->view = "dashboard.settings.customer-fee.";
        $this->settingCustomerFeeService = new SettingCustomerFeeService();
    }

    public function index(Request $request)
    {
        $response = $this->settingCustomerFeeService->index($request);

        $mark = SettingCustomerFeeEnum::mark();
        $type = SettingCustomerFeeEnum::type();

        $data = [
            'table' => $response->data,
            'type' => $type,
            'mark' => $mark,
        ];

        return view($this->view . 'index', $data);
    }

    public function create()
    {
        $mark = SettingCustomerFeeEnum::mark();
        $type = SettingCustomerFeeEnum::type();

        $data = [
            'type' => $type,
            'mark' => $mark,
        ];

        return view($this->view . "create", $data);
    }

    public function show($id)
    {
        $result = $this->settingCustomerFeeService->show($id);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route($this->route . 'index')->withInput();
        }
        $result = $result->data;

        $data = [
            'result' => $result,
        ];

        return view($this->view . "show", $data);
    }

    public function edit($id)
    {
        $result = $this->settingCustomerFeeService->show($id);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route($this->route . 'index')->withInput();
        }
        $result = $result->data;

        $mark = SettingCustomerFeeEnum::mark();
        $type = SettingCustomerFeeEnum::type();

        $data = [
            'result' => $result,
            'type' => $type,
            'mark' => $mark,
        ];

        return view($this->view . "edit", $data);
    }

    public function store(StoreRequest $request)
    {
        try {
            $response = $this->settingCustomerFeeService->store($request);
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
            $response = $this->settingCustomerFeeService->update($request, $id);
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
            $response = $this->settingCustomerFeeService->delete($id);
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
