<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Http\Requests\UserBank\StoreRequest;
use App\Http\Requests\UserBank\UpdateRequest;
use App\Models\UserBank;
use App\Services\UserBankService;
use App\Services\UserService;
use App\Services\BankService;
use Log;
use Auth;

class UserBankControLler extends Controller
{
    protected $route;
    protected $view;
    protected $userBankService;
    protected $userService;
    protected $bankService;

    public function __construct()
    {
        $this->route = "dashboard.user-banks.";
        $this->view = "dashboard.user-banks.";
        $this->userBankService = new UserBankService();
        $this->userService = new UserService();
        $this->bankService = new BankService();
    }

    public function index(Request $request)
    {
        $response = $this->userBankService->index($request);

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
        $users = $this->userService->getUserOwnerAgen();
        $users = $users->data;

        $banks = $this->bankService->index(new Request([]),false);
        $banks = $banks->data;

        $data = [
            'users' => $users,
            'banks' => $banks,
        ];

        return view($this->view . "create", $data);
    }

    public function show($id)
    {
        $result = $this->userBankService->show($id);
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
        $result = $this->userBankService->show($id);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route($this->route . 'index')->withInput();
        }
        $result = $result->data;

        $users = $this->userService->getUserOwnerAgen();
        $users = $users->data;

        $banks = $this->bankService->index(new Request([]),false);
        $banks = $banks->data;

        $data = [
            'users' => $users,
            'banks' => $banks,
            'result' => $result
        ];

        return view($this->view . "edit", $data);
    }

    public function store(StoreRequest $request)
    {
        try {
            $response = $this->userBankService->store($request);
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
            $response = $this->userBankService->update($request, $id);
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
            $response = $this->userBankService->delete($id);
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
