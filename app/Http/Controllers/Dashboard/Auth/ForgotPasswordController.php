<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Services\Auth\ForgotPasswordService;
use Auth;
use Error;

class ForgotPasswordController extends Controller
{
    protected $route;
    protected $view;
    protected $forgotPasswordService;

    public function __construct()
    {
        $this->route = "dashboard.auth.forgot-password.";
        $this->view = "dashboard.auth.";
        $this->forgotPasswordService = new ForgotPasswordService();
    }

    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }

        return view($this->view . "forgot-password");
    }

    public function post(ForgotPasswordRequest $request)
    {
        try {
            $response = $this->forgotPasswordService->forgotPassword($request);
            if (!$response->success) {
                alert()->error('Gagal', $response->message);
                return redirect()->route($this->route . 'index')->withInput();
            }

            alert()->html('Berhasil', $response->message, 'success');
            return redirect()->route('dashboard.auth.login.index');
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            alert()->error('Gagal', $th->getMessage());
            return redirect()->route($this->route . 'index')->withInput();
        }
    }
}
