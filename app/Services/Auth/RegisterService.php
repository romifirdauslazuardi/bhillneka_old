<?php

namespace App\Services\Auth;

use App\Services\BaseService;
use Illuminate\Http\Request;
use App\Enums\RoleEnum;
use App\Enums\UserEnum;
use App\Helpers\CodeHelper;
use App\Helpers\SettingHelper;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use App\Mail\RegisterMail;
use App\Models\SettingFee;
use Auth;
use DB;
use Mail;

/**
 * Class LoginService.
 */
class RegisterService extends BaseService
{
    protected $user;
    protected $settingFee;

    public function __construct()
    {
        $this->user = new User();
        $this->settingFee = new SettingFee();
    }
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $email = (empty($request->email)) ? null : trim(strip_tags($request->email));
            $password = (empty($request->password)) ? null : trim(strip_tags($request->password));
            $name = (empty($request->name)) ? null : trim(strip_tags($request->name));
            $phone = (empty($request->phone)) ? null : trim(strip_tags($request->phone));
            
            $user = $this->user->create([
                'code' => CodeHelper::generateUserCode(),
                'email' => $email,
                'password' => $password,
                'name' => $name,
                'phone' => $phone,
                'provider' => UserEnum::PROVIDER_MANUAL,
            ]);

            $user->assignRole(RoleEnum::AGEN);

            Mail::to($user->email)->send(new RegisterMail(SettingHelper::settingFee()));

            event(new Registered($user));

            DB::commit();

            return $this->response(true, "Register berhasil. Silahkan cek email anda untuk verifikasi akun");
        } catch (\Throwable $th) {
            DB::rollback();
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
