<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Http\Requests\ProductCategory\StoreRequest;
use App\Http\Requests\ProductCategory\UpdateRequest;
use App\Services\ProductCategoryService;
use App\Services\UserService;
use Log;
use Auth;

class ProductCategoryControLler extends Controller
{
    protected $route;
    protected $view;
    protected $productCategoryService;
    protected $userService;

    public function __construct()
    {
        $this->route = "dashboard.product-categories.";
        $this->view = "dashboard.product-categories.";
        $this->productCategoryService = new ProductCategoryService();
        $this->userService = new UserService();
    }

    public function index(Request $request)
    {
        $response = $this->productCategoryService->index($request);

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

        $data = [
            'users' => $users,
        ];

        return view($this->view . "create", $data);
    }

    public function show($id)
    {
        $result = $this->productCategoryService->show($id);
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
        $result = $this->productCategoryService->show($id);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route($this->route . 'index')->withInput();
        }
        $result = $result->data;

        $users = $this->userService->getUserAgen();
        $users = $users->data;

        $data = [
            'users' => $users,
            'result' => $result
        ];

        return view($this->view . "edit", $data);
    }

    public function store(StoreRequest $request)
    {
        try {
            $response = $this->productCategoryService->store($request);
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
            $response = $this->productCategoryService->update($request, $id);
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
            $response = $this->productCategoryService->delete($id);
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
