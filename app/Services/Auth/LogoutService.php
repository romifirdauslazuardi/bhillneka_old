<?php

namespace App\Services\Auth;

use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;

/**
 * Class LoginService.
 */
class LogoutService extends BaseService
{
    public function logout()
    {
        try {

            if (Auth::check()) {
                Auth::logout();
            }

            return $this->response(true, "Logout berhasil");
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
