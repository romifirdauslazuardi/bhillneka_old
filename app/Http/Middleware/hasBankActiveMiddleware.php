<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\RoleEnum;
use App\Helpers\SettingHelper;
use Auth;

class hasBankActiveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (empty($user) || $user->hasRole([RoleEnum::AGEN,RoleEnum::ADMIN_AGEN])) {

            $checkExistBank = SettingHelper::hasBankActive();

            if($checkExistBank == false){
                alert()->html('Gagal', "Rekening bank belum ada yang disetujui owner . Silahkan tambahkan rekening terlebih dahulu dan tunggu hingga owner approve data anda", 'error');
                return redirect()->route('dashboard.user-banks.index');
            }
        }

        return $next($request);
    }
}
