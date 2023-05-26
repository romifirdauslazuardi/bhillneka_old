<?php

namespace App\Services\Auth;

use App\Services\BaseService;
use Illuminate\Http\Request;
use App\Enums\RoleEnum;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use Auth;
use DB;

/**
 * Class LoginService.
 */
class RegisterService extends BaseService
{
    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $email = (empty($request->input("email"))) ? null : trim(htmlentities($request->input("email")));
            $password = (empty($request->input("password"))) ? null : trim(htmlentities($request->input("password")));
            $name = (empty($request->input("name"))) ? null : trim(htmlentities($request->input("name")));
            $phone = (empty($request->input("phone"))) ? null : trim(htmlentities($request->input("phone")));
            
            $user = $this->user->create([
                'email' => $email,
                'password' => $password,
                'name' => $name,
                'phone' => $phone,
            ]);

            $user->assignRole(RoleEnum::AGEN);

            event(new Registered($user));

            DB::commit();

            return $this->response(true, "Register berhasil");
        } catch (\Throwable $th) {
            DB::rollback();
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
