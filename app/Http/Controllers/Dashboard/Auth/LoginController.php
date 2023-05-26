<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\RoleEnum;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Log;
use App\Services\Auth\LoginService;
use Auth;
use Error;

class LoginController extends Controller
{
    protected $route;
    protected $view;
    protected $loginService;

    public function __construct()
    {
        $this->route = "dashboard.auth.login.";
        $this->view = "dashboard.auth.";
        $this->loginService = new LoginService();
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
        return view($this->view . "login");
    }

    public function post(LoginRequest $request)
    {
        try {
            $response = $this->loginService->login($request);
            if (!$response->success) {
                alert()->error('Gagal', $response->message);
                return redirect()->route($this->route . 'index')->withInput();
            }

            alert()->html('Berhasil', $response->message, 'success');
            return redirect()->intended(route('dashboard.index'));
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            alert()->html('Gagal', $th->getMessage(), 'error');
            return redirect()->route($this->route . 'index');
        }
    }
}
