<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Auth\LogoutService;
use Auth;
use Log;

class LogoutController extends Controller
{
    protected $logoutService;
    protected $route;

    public function __construct()
    {
        $this->route = "dashboard.auth.";
        $this->logoutService = new LogoutService();
    }

    public function logout()
    {
        try {

            $manager = app('impersonate');

            if ($manager->isImpersonating()) {
                Auth::user()->leaveImpersonation();
                return redirect()->route('dashboard.index');
            } else {
                $response = $this->logoutService->logout();
                if (!$response->success) {
                    alert()->error('Gagal', $response->message);
                    return redirect()->route($this->route . 'login.index')->withInput();
                }

                alert()->html('Berhasil', $response->message, 'success');
                return redirect()->route($this->route . 'login.index');
            }
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            alert()->error('Gagal', $th->getMessage());
            return redirect()->route($this->route . 'login.index')->withInput();
        }
    }
}
