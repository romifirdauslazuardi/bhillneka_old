<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\NewsEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Helpers\SettingHelper;
use App\Http\Requests\News\StoreRequest;
use App\Http\Requests\News\UpdateRequest;
use App\Models\News;
use App\Services\NewsService;
use App\Services\UserService;
use Log;
use Auth;

class NewsController extends Controller
{
    protected $route;
    protected $view;
    protected $newsService;
    protected $userService;

    public function __construct()
    {
        $this->route = "dashboard.news.";
        $this->view = "dashboard.news.";
        $this->newsService = new NewsService();
        $this->userService = new UserService();

        $this->middleware(function ($request, $next) {
            if(empty(Auth::user()->business_id)){
                if($request->wantsJson()){
                    return ResponseHelper::apiResponse(false, "Bisnis page belum diaktifkan");
                }
                alert()->error('Gagal', "Bisnis page belum diaktifkan");
                return redirect()->route("dashboard.index");
            }
            return $next($request);
        },['only' => ['create','edit','store','destroy']]);

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
        },['only' => ['index','show','create','store']]);

        $this->middleware(function ($request, $next) {
            if(SettingHelper::hasBankActive()==false){
                if($request->wantsJson()){
                    return ResponseHelper::apiResponse(false, "Tidak ada rekening bank anda yang sudah diverifikasi oleh owner");
                }
                alert()->error('Gagal', "Tidak ada rekening bank anda yang sudah diverifikasi oleh owner");
                return redirect()->route("dashboard.index");
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $response = $this->newsService->index($request);

        $users = $this->userService->index(new Request(['role' => RoleEnum::AGEN]),false);
        $users = $users->data;

        $data = [
            'table' => $response->data,
            'users' => $users,
        ];

        return view($this->view . 'index', $data);
    }

    public function create()
    {
        $users = $this->userService->getUserCustomer(new Request([]));
        $users = $users->data;

        $data = [
            'users' => $users,
        ];

        return view($this->view . "create",$data);
    }

    public function show($id)
    {
        $result = $this->newsService->show($id);
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
        $result = $this->newsService->show($id);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route($this->route . 'index')->withInput();
        }
        $result = $result->data;

        $users = $this->userService->getUserCustomer(new Request([]));
        $users = $users->data;

        $data = [
            'result' => $result,
            'users' => $users,
        ];

        return view($this->view . "edit", $data);
    }

    public function store(StoreRequest $request)
    {
        try {
            $response = $this->newsService->store($request);
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
            $response = $this->newsService->update($request, $id);
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
            $response = $this->newsService->delete($id);
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
