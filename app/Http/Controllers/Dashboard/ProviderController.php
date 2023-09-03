<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ProviderEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Provider\StoreRequest;
use App\Http\Requests\Provider\UpdateRequest;
use App\Models\Provider;
use App\Services\ProviderService;
use App\Services\UserService;
use Log;
use Auth;

class ProviderController extends Controller
{
    protected $route;
    protected $view;
    protected $providerService;
    protected $userService;

    public function __construct()
    {
        $this->route = "dashboard.providers.";
        $this->view = "dashboard.providers.";
        $this->providerService = new ProviderService();
        $this->userService = new UserService();
    }

    public function index(Request $request)
    {
        $response = $this->providerService->index($request);

        $data = [
            'table' => $response->data,
        ];

        return view($this->view . 'index', $data);
    }

    public function create()
    {
        $status = ProviderEnum::status();
        $type = ProviderEnum::type();

        $data = [
            'status' => $status,
            'type' => $type,
        ];

        return view($this->view . "create", $data);
    }

    public function show($id)
    {
        $result = $this->providerService->show($id);
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
        $result = $this->providerService->show($id);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route($this->route . 'index')->withInput();
        }
        $result = $result->data;

        $status = ProviderEnum::status();
        $type = ProviderEnum::type();

        $data = [
            'result' => $result,
            'status' => $status,
            'type' => $type,
        ];

        return view($this->view . "edit", $data);
    }

    public function store(StoreRequest $request)
    {
        try {
            $response = $this->providerService->store($request);
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
            $response = $this->providerService->update($request, $id);
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
            $response = $this->providerService->delete($id);
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
