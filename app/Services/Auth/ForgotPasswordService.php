<?php

namespace App\Services\Auth;

use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Auth;
use Error;

/**
 * Class ForgotPasswordService.
 */
class ForgotPasswordService extends BaseService
{
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {

            $email = (empty($request->input("email"))) ? null : trim(htmlentities($request->input("email")));

            $status = Password::sendResetLink(
                [
                    'email' => $email,
                ]
            );

            if ($status != Password::RESET_LINK_SENT) {
                return $this->response(false, "Terjadi kesalahan saat mengirim link reset password");
            }

            return $this->response(true, "Link reset password telah dikirim ke email anda");
        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
