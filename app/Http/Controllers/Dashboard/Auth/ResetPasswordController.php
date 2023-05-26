<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\Auth\ResetPasswordService;
use Auth;
use Error;

class ResetPasswordController extends Controller
{
    protected $route;
    protected $view;
    protected $resetPasswordService;

    public function __construct()
    {
        $this->route = "dashboard.auth.reset-password.";
        $this->view = "dashboard.auth.";
        $this->resetPasswordService = new ResetPasswordService();
    }

    public function index(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }

        $data = [
            'email' => $request->input('email'),
            'token' => $request->input('token')
        ];

        return view($this->view . "reset-password", $data);
    }

    public function post(ResetPasswordRequest $request)
    {
        try {
            $response = $this->resetPasswordService->resetPassword($request);
            if (!$response->success) {
                alert()->error('Gagal', $response->message);
                return redirect()->back()->withInput();
            }

            alert()->html('Berhasil', $response->message, 'success');
            return redirect()->route('dashboard.auth.login.index');
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            alert()->error('Gagal', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
