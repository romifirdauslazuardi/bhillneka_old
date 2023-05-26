<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\RoleEnum;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Log;
use App\Services\Auth\RegisterService;
use Auth;
use Error;

class RegisterController extends Controller
{
    protected $route;
    protected $view;
    protected $registerService;

    public function __construct()
    {
        $this->route = "dashboard.auth.register.";
        $this->view = "dashboard.auth.";
        $this->registerService = new RegisterService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route("dashboard.index");
        }
        return view($this->view . "register");
    }

    public function post(RegisterRequest $request)
    {
        try {
            $response = $this->registerService->register($request);
            if (!$response->success) {
                alert()->error('Gagal', $response->message);
                return redirect()->route($this->route . 'index')->withInput();
            }

            alert()->html('Berhasil', $response->message, 'success');
            return redirect()->route('dashboard.auth.login.index');
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            alert()->html('Gagal', $th->getMessage(), 'error');
            return redirect()->route($this->route . 'index');
        }
    }
}
