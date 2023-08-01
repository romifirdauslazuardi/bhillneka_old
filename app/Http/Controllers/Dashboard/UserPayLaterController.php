<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Http\Requests\UserPayLater\StoreRequest;
use App\Services\UserPayLaterService;
use Log;
use Auth;

class UserPayLaterController extends Controller
{
    protected $route;
    protected $view;
    protected $userPayLaterService;

    public function __construct()
    {
        $this->route = "dashboard.user-pay-laters.";
        $this->view = "dashboard.user-pay-laters.";
        $this->userPayLaterService = new UserPayLaterService();

        $this->middleware(function ($request, $next) {
            if(empty(Auth::user()->business_id)){
                alert()->error('Gagal', "Bisnis page belum diaktifkan");
                return redirect()->route("dashboard.index");
            }
            return $next($request);
        },['only' => ['store']]);
    }

    public function index(Request $request)
    {
        $response = $this->userPayLaterService->index($request);
        if (!$response->success) {
            alert()->error('Gagal', $response->message);
            return redirect()->route('dashboard.index')->withInput();
        }

        $data = [
            'table' => $response->data,
        ];

        return view($this->view . 'index', $data);
    }

    public function store(StoreRequest $request)
    {
        try {
            $response = $this->userPayLaterService->store($request);
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
