<?php

namespace App\Services\Auth;

use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use App\Models\User;
use Auth;

/**
 * Class VerificationService.
 */
class VerificationService extends BaseService
{
    public function verificationResend()
    {
        try {
            $user = Auth::user();
            if ($user) {
                $user->update([
                    'email' => $user->email,
                ]);
            }
            if (!$user) {
                return $this->response(false, "Email tidak ditemukan");
            }
            if ($user->hasVerifiedEmail()) {
                return $this->response(false, "Email sudah terverifikasi");
            }
            $user->sendEmailVerificationNotification();

            return $this->response(true, "Link verifikasi email berhasil dikirim!");
        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function verifyUser(Request $request)
    {
        try {
            $user = User::findOrFail($request->route('id'));
            if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
                throw new AuthorizationException();
            }

            if ($user->hasVerifiedEmail()) {
                return $this->response(false, "Verifikasi email gagal");
            } else {
                if ($user->markEmailAsVerified()) {
                    event(new Verified($user));
                }

                return $this->response(true, "Verifikasi email berhasil");
            }
        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
