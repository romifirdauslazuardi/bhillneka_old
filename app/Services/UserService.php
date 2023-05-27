<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Services\BaseService;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Helpers\UploadHelper;
use App\Enums\UserEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use Throwable;

class UserService extends BaseService
{
    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = $request->search;
        $role = $request->role;

        $table = $this->user;
        if (!empty($search)) {
            $table = $this->user->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%');
                $query2->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        if (!empty($role)) {
            $table = $table->role($role);
        }
        if (Auth::user()->hasRole([
            RoleEnum::OWNER
        ])) {
            $table = $table->withTrashed();
        }
        if (Auth::user()->hasRole([
            RoleEnum::AGEN
        ])) {
            $table = $table->role([RoleEnum::USER]);
        }
        $table = $table->orderBy('created_at', 'DESC');

        if ($paginate) {
            $table = $table->paginate(10);
            $table = $table->withQueryString();
        } else {
            $table = $table->get();
        }

        return $this->response(true, 'Berhasil mendapatkan data', $table);
    }

    public function show($id)
    {
        try {
            $result = $this->user;
            if (Auth::user()->hasRole([
                RoleEnum::OWNER
            ])) {
                $result = $result->withTrashed();
            }
            $result = $result->findOrFail($id);

            return $this->response(true, 'Berhasil mendapatkan data', $result);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            $name = $request->name;
            $phone = $request->phone;
            $email = $request->email;
            $email_verified_at = $request->email_verified_at;
            $password = $request->password;
            $roles = $request->roles;
            $avatar = $request->file("avatar");

            if ($avatar) {
                $upload = UploadHelper::upload_file($avatar, 'user-avatar', UserEnum::AVATAR_EXT);

                if ($upload["IsError"] == TRUE) {
                    return $this->response(false, $upload["Message"]);
                }

                $avatar = $upload["Path"];
            }

            $create = $this->user->create([
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'email_verified_at' => $email_verified_at,
                'password' => bcrypt($password),
                'avatar' => $avatar,
            ]);

            $create->assignRole($roles);

            return $this->response(true, 'Berhasil menambahkan data',$create);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $name = $request->name;
            $phone = $request->phone;
            $email = $request->email;
            $email_verified_at = $request->email_verified_at;
            $password = $request->password;
            $roles = $request->roles;
            $avatar = $request->file("avatar");

            $result = $this->user;
            if (Auth::user()->hasRole([
                RoleEnum::OWNER
            ])) {
                $result = $result->withTrashed();
            }
            $result = $result->findOrFail($id);

            if ($password) {
                $password = bcrypt($password);
            } else {
                $password = $result->password;
            }

            if ($avatar) {
                $upload = UploadHelper::upload_file($avatar, 'user-avatar', UserEnum::AVATAR_EXT);

                if ($upload["IsError"] == TRUE) {
                    return $this->response(false, $upload["Message"]);
                }

                $avatar = $upload["Path"];
            } else {
                $avatar = $result->avatar;
            }

            $result->update([
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'email_verified_at' => $email_verified_at,
                'password' => $password,
                'avatar' => $avatar
            ]);

            $result->syncRoles($roles);

            return $this->response(true, 'Berhasil mengubah data',$result);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->user->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function restore($id)
    {
        try {
            $result = $this->user->withTrashed()->findOrFail($id);
            $result->restore();

            return $this->response(true, 'Berhasil mengebalikan data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function getUserAgen(){
        try {
            $table = $this->user;
            $table = $table->role([RoleEnum::AGEN]);
            $table = $table->orderBy('created_at', 'DESC');
            $table = $table->get();

            return $this->response(true, 'Berhasil mendapatkan data',$table);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function getUserOwnerAgen(){
        try {
            $table = $this->user;
            $table = $table->role([RoleEnum::OWNER,RoleEnum::AGEN]);
            $table = $table->orderBy('created_at', 'DESC');
            $table = $table->get();

            return $this->response(true, 'Berhasil mendapatkan data',$table);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
