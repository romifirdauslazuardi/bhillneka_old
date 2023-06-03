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
            $email = (empty($request->input("email"))) ? null : trim(htmlentities($request->input("email")));
            $password = (empty($request->input("password"))) ? null : trim(htmlentities($request->input("password")));
            $rememberme = (empty($request->input("rememberme"))) ? null : trim(htmlentities($request->input("rememberme")));

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
