<?php

namespace App\Services;

use App\Services\BaseService;
use App\Http\Requests\Profile\UpdateRequest;
use App\Http\Requests\Profile\UpdateBusinessPageRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\UploadHelper;
use App\Enums\UserEnum;
use Auth;
use DB;
use Log;
use Throwable;

class ProfileService extends BaseService
{
    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function update(UpdateRequest $request)
    {
        try {
            $result = Auth::user();

            $name = (empty($request->name)) ? null : trim(strip_tags($request->name));
            $phone = (empty($request->phone)) ? null : trim(strip_tags($request->phone));
            $email = (empty($request->email)) ? null : trim(strip_tags($request->email));
            $password = (empty($request->password)) ? null : trim(strip_tags($request->password));
            $avatar = $request->file("avatar");

            if ($avatar) {
                $upload = UploadHelper::upload_file($avatar, 'user-avatar', UserEnum::AVATAR_EXT);

                if ($upload["IsError"] == TRUE) {
                    return $this->response(false, $upload["Message"]);
                }

                $avatar = $upload["Path"];
            } else {
                $avatar = $result->avatar;
            }

            if ($password) {
                $password = bcrypt($password);
            } else {
                $password = $result->password;
            }

            $result->update([
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'password' => $password,
                'avatar' => $avatar,
            ]);

            return $this->response(true, "Data berhasil diubah");
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function updateBusinessPage(UpdateBusinessPageRequest $request)
    {
        try {
            $result = Auth::user();

            $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));

            $result->update([
                'business_id' => $business_id,
            ]);

            return $this->response(true, "Business page berhasil diubah");
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
