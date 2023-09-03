<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Business\StoreRequest;
use App\Http\Requests\Business\UpdateRequest;
use App\Services\BusinessService;
use App\Services\UserService;
use App\Services\BusinessCategoryService;
use Log;
use Auth;
use Illuminate\Support\Facades\Http;

class BusinessController extends Controller
{
    protected $route;
    protected $view;
    protected $businessService;
    protected $userService;
    protected $businessCategoryService;

    public function __construct()
    {
        $this->route = "dashboard.business.";
        $this->view = "dashboard.business.";
        $this->businessService = new BusinessService();
        $this->userService = new UserService();
        $this->businessCategoryService = new BusinessCategoryService();
    }

    public function index(Request $request)
    {
        $response = $this->businessService->index($request);

        $users = $this->userService->index(new Request(['role' => RoleEnum::AGEN]),false);
        $users = $users->data;

        $data = [
            'table' => $response->data,
            'users' => $users
        ];

        return view($this->view . 'index', $data);
    }

    public function create()
    {
        $users = $this->userService->getUserAgen();
        $users = $users->data;

        $categories = $this->businessCategoryService->index(new Request([]),false);
        $categories = $categories->data;

        $data = [
            'users' => $users,
            'categories' => $categories,
        ];

        return view($this->view . "create", $data);
    }

    public function show($id)
    {
        $result = $this->businessService->show($id);
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
        $result = $this->businessService->show($id);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route($this->route . 'index')->withInput();
        }
        $result = $result->data;

        $users = $this->userService->getUserAgen();
        $users = $users->data;

        $categories = $this->businessCategoryService->index(new Request([]),false);
        $categories = $categories->data;

        $data = [
            'users' => $users,
            'categories' => $categories,
            'result' => $result
        ];

        return view($this->view . "edit", $data);
    }

    public function store(StoreRequest $request)
    {
        try {
            $response = $this->businessService->store($request);
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
            $response = $this->businessService->update($request, $id);
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
            $response = $this->businessService->delete($id);
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

    public function qrcode($id)
    {
        $result = $this->businessService->show($id);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route($this->route . 'index')->withInput();
        }
        $result = $result->data;

        $data = [
            'result' => $result
        ];

        return view($this->view . "qrcode", $data);
    }
}
