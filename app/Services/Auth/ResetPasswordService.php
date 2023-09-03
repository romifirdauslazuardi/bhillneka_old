<?php

namespace App\Services\Auth;

use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Auth;
use DB;

/**
 * Class LoginService.
 */
class ResetPasswordService extends BaseService
{
    public function resetPassword(ResetPasswordRequest $request)
    {
        DB::beginTransaction();
        try {

            $email = (empty($request->email)) ? null : trim(strip_tags($request->email));
            $password = (empty($request->password)) ? null : trim(strip_tags($request->password));
            $password_confirmation = (empty($request->password_confirmation)) ? null : trim(strip_tags($request->password_confirmation));
            $token = (empty($request->token)) ? null : trim(strip_tags($request->token));

            $status = Password::reset(
                [
                    'email' => $email,
                    'password' => $password,
                    'password_confirmation' => $password_confirmation,
                    'token' => $token,
                ],
                function ($user, $newPassword) {
                    $user->forceFill([
                        'password' => bcrypt($newPassword),
                    ])->setRememberToken(Str::random(60));

                    $user->save();

                    event(new PasswordReset($user));
                }
            );

            if ($status != Password::PASSWORD_RESET) {
                return $this->response(false, "Terjadi kesalahan saat mengubah password");
            }

            DB::commit();

            return $this->response(true, "Password berhasil diubah");
        } catch (\Throwable $e) {
            DB::rollback();
            Log::emergency($e->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
