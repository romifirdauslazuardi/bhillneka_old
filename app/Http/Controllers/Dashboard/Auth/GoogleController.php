<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Enums\UserEnum;
use Auth;
use Log;

class GoogleController extends Controller
{
    protected $route;
    protected $view;
    protected $user;

    public function __construct()
    {
        $this->route = "dashboard.auth.login.";
        $this->view = "dashboard.auth.";
        $this->user = new User();
    }

    public function index()
    {
        if (Auth::check()) {
            return redirect()->route("dashboard.index");
        }

        return Socialite::driver("google")->redirect();
    }

    public function callback(){
        try {
            $google = Socialite::driver('google')->user();

            $existUser = $this->user;
            $existUser = $existUser->where("email",$google->email);
            $existUser = $existUser->first();

            if($existUser){
                Auth::login($existUser,true);
                alert()->html('Berhasil', "Login berhasil", 'success');
                return redirect()->intended(route('dashboard.index'));
            }

            $create = $this->user->create([
                'code' => $google->id,
                'name' => $google->name,
                'email' => $google->email,
                'email_verified_at' => date("Y-m-d H:i:s"),
                'password' => bcrypt("123456789"),
                'provider' => UserEnum::PROVIDER_GOOGLE,
            ]);

            $create->assignRole([RoleEnum::AGEN]);

            Auth::login($create,true);

            alert()->html('Berhasil', "Login berhasil", 'success');
            return redirect()->intended(route('dashboard.index'));

        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            alert()->html('Gagal', $th->getMessage(), 'error');
            return redirect()->route($this->route . 'index');
        }
    }
}
