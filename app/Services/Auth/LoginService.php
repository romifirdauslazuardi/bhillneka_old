<?php

namespace App\Services\Auth;

use App\Services\BaseService;
use Illuminate\Http\Request;
use App\Enums\RoleEnum;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Log;
use Auth;

/**
 * Class LoginService.
 */
class LoginService extends BaseService
{
    public function login(LoginRequest $request)
    {
        try {
            $email = (empty($request->email)) ? null : trim(strip_tags($request->email));
            $password = (empty($request->password)) ? null : trim(strip_tags($request->password));
            $rememberme = (empty($request->rememberme)) ? null : trim(strip_tags($request->rememberme));

            $field = [
                'email' => $email,
                'password' => $password,
            ];

            if (Auth::attempt($field, $rememberme)) {
                if (!Auth::user()->hasRole([
                    RoleEnum::OWNER,
                    RoleEnum::AGEN,
                    RoleEnum::USER,
                    RoleEnum::ADMIN_AGEN,
                ])) {
                    Auth::logout();
                    return $this->response(true, "Login berhasil");
                }
            } else {
                return $this->response(false, "Username / password tidak sesuai");
            }

            return $this->response(true, "Login berhasil");
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
