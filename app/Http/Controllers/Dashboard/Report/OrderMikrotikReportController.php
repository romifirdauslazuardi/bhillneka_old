<?php

namespace App\Http\Controllers\Dashboard\Report;

use App\Enums\OrderMikrotikEnum;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\UpdateOrderMikrotikRequest;
use App\Services\Report\OrderMikrotikReportService;
use App\Services\MikrotikConfigService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Excel;
use Illuminate\Http\Response;
use Log;

class OrderMikrotikReportController extends Controller
{
    protected $route;
    protected $view;
    protected $orderMikrotikReportService;
    protected $mikrotikConfigService;

    public function __construct()
    {
        $this->route = "dashboard.reports.order-mikrotiks.";
        $this->view = "dashboard.reports.order-mikrotiks.";
        $this->orderMikrotikReportService = new OrderMikrotikReportService();
        $this->mikrotikConfigService = new MikrotikConfigService();
    }

    public function index(Request $request)
    {
        $response = $this->orderMikrotikReportService->index($request);
        $response = $response->data;

        $type = OrderMikrotikEnum::type();

        $data = [
            'table' => $response,
            'type' => $type,
        ];

        return view($this->view . 'index', $data);
    }

    public function show($id)
    {
        $result = $this->orderMikrotikReportService->show($id);
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
        $result = $this->orderMikrotikReportService->show($id);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route($this->route . 'index')->withInput();
        }
        $result = $result->data;

        $data = [
            'result' => $result,
        ];

        return view($this->view . "edit", $data);
    }

    public function update(UpdateOrderMikrotikRequest $request, $id)
    {
        try {
            $response = $this->orderMikrotikReportService->update($request, $id);
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
            $response = $this->orderMikrotikReportService->delete($id);
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
