<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use App\Models\User;
use App\Services\Auth\VerificationService;
use Auth;

class VerificationController extends Controller
{
    protected $route;
    protected $view;
    protected $user;
    protected $verificationService;

    public function __construct()
    {
        $this->route = "dashboard.auth.verification";
        $this->view = "dashboard.auth.verification.";
        $this->user = new User();
        $this->verificationService = new VerificationService();
    }

    public function verificationNotice()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard.auth.login.index');
        }

        return view($this->view . 'notice');
    }

    public function verificationResend()
    {
        try {
            $response = $this->verificationService->verificationResend();
            if (!$response->success) {
                alert()->error('Gagal', $response->message);
                return redirect()->back()->withInput();
            }

            alert()->html('Berhasil', $response->message, 'success');
            return redirect()->route('dashboard.auth.verification.notice');
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            alert()->error('Gagal', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function verifyUser(Request $request)
    {
        try {
            $response = $this->verificationService->verifyUser($request);
            if (!$response->success) {
                alert()->error('Gagal', $response->message);
                return redirect()->back()->withInput();
            }

            alert()->html('Berhasil', $response->message, 'success');
            return view($this->view . 'verify');
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            alert()->error('Gagal', $th->getMessage());
            return view($this->view . 'verify');
        }
    }
}
