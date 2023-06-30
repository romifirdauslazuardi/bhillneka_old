<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Services\BaseService;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Helpers\UploadHelper;
use App\Enums\UserEnum;
use App\Helpers\CodeHelper;
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
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));
        $role = (empty($request->role)) ? null : trim(strip_tags($request->role));
        $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));

        if(Auth::user()->hasRole([RoleEnum::AGEN])){
            $user_id = Auth::user()->id;
        }

        if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
            $user_id = Auth::user()->user_id;
        }

        if(!empty(Auth::user()->business_id)){}

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
            $table = $table->where(function($query2){
                $query2->role([RoleEnum::ADMIN_AGEN]);
                $query2->orWhere(function($query3){
                    $query3->role([RoleEnum::USER]);
                    $query3->where("business_id",Auth::user()->business_id);
                });
            });
        }
        if (Auth::user()->hasRole([
            RoleEnum::ADMIN_AGEN
        ])) {
            $table = $table->where(function($query2){
                $query2->where(function($query3){
                    $query3->role([RoleEnum::USER]);
                    $query3->where("business_id",Auth::user()->business_id);
                });
            });
        }
        if(!empty($user_id)){
            $table = $table->where("user_id",$user_id);
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
            if (Auth::user()->hasRole([
                RoleEnum::AGEN
            ])) {
                $result = $result->role([RoleEnum::USER,RoleEnum::ADMIN_AGEN]);
                $result = $result->where('user_id',Auth::user()->id);
            }
            if (Auth::user()->hasRole([
                RoleEnum::ADMIN_AGEN
            ])) {
                $result = $result->role([RoleEnum::USER]);
                $result = $result->where('user_id',Auth::user()->user_id);
            }
            $result = $result->where("id",$id);
            $result = $result->first();

            if(!$result){
                return $this->response(false, "Data tidak ditemukan");
            }

            return $this->response(true, 'Berhasil mendapatkan data', $result);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $name = (empty($request->name)) ? null : trim(strip_tags($request->name));
            $phone = (empty($request->phone)) ? null : trim(strip_tags($request->phone));
            $email = (empty($request->email)) ? null : trim(strip_tags($request->email));
            $email_verified_at = (empty($request->email_verified_at)) ? null : trim(strip_tags($request->email_verified_at));
            $password = (empty($request->password)) ? null : trim(strip_tags($request->password));
            $roles = (empty($request->roles)) ? null : trim(strip_tags($request->roles));
            $avatar = $request->file("avatar");
            $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
            $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));

            if ($avatar) {
                $upload = UploadHelper::upload_file($avatar, 'user-avatar', UserEnum::AVATAR_EXT);

                if ($upload["IsError"] == TRUE) {
                    return $this->response(false, $upload["Message"]);
                }

                $avatar = $upload["Path"];
            }

            $create = $this->user->create([
                'code' => CodeHelper::generateUserCode(),
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'email_verified_at' => $email_verified_at,
                'password' => bcrypt($password),
                'avatar' => $avatar,
                'user_id' => $user_id,
                'business_id' => $business_id,
                'author_id' => Auth::user()->id,
            ]);

            $create->assignRole($roles);

            DB::commit();

            return $this->response(true, 'Berhasil menambahkan data',$create);
        } catch (Throwable $th) {
            DB::rollback();
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $name = (empty($request->name)) ? null : trim(strip_tags($request->name));
            $phone = (empty($request->phone)) ? null : trim(strip_tags($request->phone));
            $email = (empty($request->email)) ? null : trim(strip_tags($request->email));
            $email_verified_at = (empty($request->email_verified_at)) ? null : trim(strip_tags($request->email_verified_at));
            $password = (empty($request->password)) ? null : trim(strip_tags($request->password));
            $roles = (empty($request->roles)) ? null : trim(strip_tags($request->roles));
            $avatar = $request->file("avatar");
            $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
            $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));

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
                'avatar' => $avatar,
                'user_id' => $user_id,
                'business_id' => $business_id,
            ]);

            $result->syncRoles($roles);

            DB::commit();

            return $this->response(true, 'Berhasil mengubah data',$result);
        } catch (Throwable $th) {
            DB::rollback();;
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

    public function getUserCustomer(Request $request,array $column = []){
        try {
            $user_id = $request->user_id;

            if(Auth::user()->hasRole([RoleEnum::AGEN])){
                $user_id = Auth::user()->id;
            }

            $table = $this->user;
            if(count($column) >= 1){
                $table = $table->select($column);
            }
            if(!empty($user_id)){
                $table = $table->where("user_id",$user_id);
            }
            $table = $table->role([RoleEnum::USER]);
            $table = $table->orderBy('created_at', 'DESC');
            $table = $table->get();            

            return $this->response(true, 'Berhasil mendapatkan data',$table);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, $th->getMessage());
        }
    }
}
