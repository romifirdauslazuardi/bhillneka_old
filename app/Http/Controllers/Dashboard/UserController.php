<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Services\UserService;
use App\Enums\RoleEnum;
use App\Helpers\SettingHelper;
use Log;
use Auth;

class UserController extends Controller
{
    protected $route;
    protected $view;
    protected $userService;

    public function __construct()
    {
        $this->route = "dashboard.users.";
        $this->view = "dashboard.users.";
        $this->userService = new UserService();

        $this->middleware(function ($request, $next) {
            if(Auth::user()->hasRole([
                RoleEnum::AGEN,
                RoleEnum::ADMIN_AGEN]) 
            && empty(Auth::user()->business_id)){
                if($request->wantsJson()){
                    return ResponseHelper::apiResponse(false, "Bisnis page belum diaktifkan");
                }
                alert()->error('Gagal', "Bisnis page belum diaktifkan");
                return redirect()->route("dashboard.index");
            }
            return $next($request);
        },['only' => ['index','show','create','edit','store','update','destroy']]);

        $this->middleware(function ($request, $next) {
            if(SettingHelper::hasBankActive()==false){
                if($request->wantsJson()){
                    return ResponseHelper::apiResponse(false, "Tidak ada rekening bank yang sudah diverifikasi owner");
                }
                alert()->error('Gagal', "Tidak ada rekening bank anda yang sudah diverifikasi oleh owner");
                return redirect()->route("dashboard.index");
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $response = $this->userService->index($request);

        $roles = RoleEnum::roles();

        $data = [
            'table' => $response->data,
            'roles' => $roles
        ];

        return view($this->view . 'index', $data);
    }

    public function create()
    {
        $roles = RoleEnum::roles();

        $users = $this->userService->getUserAgen();
        $users = $users->data;

        $data = [
            'roles' => $roles,
            'users' => $users,
        ];

        return view($this->view . "create", $data);
    }

    public function show($id)
    {
        $result = $this->userService->show($id);
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
        $result = $this->userService->show($id);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route($this->route . 'index')->withInput();
        }
        $result = $result->data;

        $roles = RoleEnum::roles();

        $users = $this->userService->getUserAgen();
        $users = $users->data;

        $data = [
            'roles' => $roles,
            'result' => $result,
            'users' => $users,
        ];

        return view($this->view . "edit", $data);
    }

    public function store(StoreRequest $request)
    {
        try {
            $response = $this->userService->store($request);
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
            $response = $this->userService->update($request, $id);
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
            $response = $this->userService->delete($id);
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

    public function restore($id)
    {
        try {
            $response = $this->userService->restore($id);
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

    public function impersonate($id)
    {
        try {
            $response = $this->userService->show($id);
            if (!$response->success) {
                alert()->error('Gagal', $response->message);
                return redirect()->route($this->route . 'index')->withInput();
            }
            $response = $response->data;
            Auth::user()->impersonate($response);

            alert()->html('Berhasil', "Berhasil login dengan akun user", 'success');
            return redirect()->route('dashboard.index');
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            alert()->error('Gagal', $th->getMessage());
            return redirect()->route($this->route . 'index')->withInput();
        }
    }
}
