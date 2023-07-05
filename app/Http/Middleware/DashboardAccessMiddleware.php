<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\RoleEnum;
use Auth;

class DashboardAccessMiddleware
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
        if (empty($user) || !$user->hasRole([RoleEnum::OWNER, RoleEnum::AGEN,RoleEnum::CUSTOMER,RoleEnum::ADMIN_AGEN])) {

            alert()->html('Gagal', "Anda tidak diperbolehkan mengakses halaman ini", 'error');

            if ($user) {
                Auth::logout();
            }
            return redirect()->route('dashboard.auth.login.index');
        }

        return $next($request);
    }
}
