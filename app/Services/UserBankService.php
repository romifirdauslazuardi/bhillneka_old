<?php

namespace App\Services;

use App\Services\BaseService;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\UserBank\StoreRequest;
use App\Http\Requests\UserBank\UpdateRequest;
use App\Models\UserBank;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\RoleEnum;
use App\Enums\UserBankEnum;
use App\Notifications\UserBankNotification;
use Auth;
use DB;
use Log;
use Throwable;

class UserBankService extends BaseService
{
    protected $userBank;
    protected $user;

    public function __construct()
    {
        $this->userBank = new UserBank();
        $this->user = new User();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = $request->search;
        $user_id = $request->user_id;
        $status = $request->status;

        if(Auth::user()->hasRole([RoleEnum::AGEN])){
            $user_id = Auth::user()->id;
        }

        $table = $this->userBank;
        if (!empty($search)) {
            $table = $this->userBank->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%');
                $query2->orWhere('number', 'like', '%' . $search . '%');
            });
        }
        if(!empty($user_id)){
            $table = $table->where("user_id",$user_id);
        }
        if(isset($status)){
            $table = $table->where("status",$status);
        }
        if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
            $table = $table->where("user_id",Auth::user()->user_id);
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
            $result = $this->userBank;
            if(Auth::user()->hasRole([RoleEnum::AGEN])){
                $result = $result->where("user_id",Auth::user()->id);
            }
            if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
                $result = $result->where("user_id",Auth::user()->user_id);
            }
            $result = $result->where('id',$id);
            $result = $result->first();

            if(!$result){
                return $this->response(false, "Data tidak ditemukan");
            }

            $related = $this->userBank;
            $related = $related->where("user_id",$result->user_id);
            $related = $related->where("id","!=",$result->id);
            $related = $related->orderBy("created_at","DESC");
            $related = $related->get();

            $result->related = $related;

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
            $name = $request->name;
            $number = $request->number;
            $bank_id = $request->bank_id;
            $user_id = $request->user_id;
            $status = $request->status;

            $create = $this->userBank->create([
                'name' => $name,
                'number' => $number,
                'bank_id' => $bank_id,
                'user_id' => $user_id,
                'status' => $status,
                'author_id' => Auth::user()->id,
            ]);

            if(Auth::user()->hasRole([RoleEnum::AGEN,RoleEnum::ADMIN_AGEN])){
                $owners = $this->user;
                $owners = $owners->role([RoleEnum::OWNER]);
                $owners = $owners->get();

                Notification::send($owners,new UserBankNotification(route('dashboard.user-banks.show',$create->id),"Pengajuan Rekening Baru","Terdapat pengajuan rekening baru dari ".Auth::user()->name.". Silahkan approve / reject pengajuan ini"));
            }

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
            $name = $request->name;
            $number = $request->number;
            $bank_id = $request->bank_id;
            $user_id = $request->user_id;
            $status = $request->status;

            $result = $this->userBank->findOrFail($id);

            if(Auth::user()->hasRole([RoleEnum::AGEN,RoleEnum::ADMIN_AGEN])){
                $status = UserBankEnum::STATUS_WAITING_APPROVE;

                $owners = $this->user;
                $owners = $owners->role([RoleEnum::OWNER]);
                $owners = $owners->get();

                Notification::send($owners,new UserBankNotification(route('dashboard.user-banks.show',$result->id),"Perubahan Data Rekening","Terdapat perubahan rekening baru dari ".Auth::user()->name.". Silahkan approve / reject perubahan ini"));
            }            

            $result->update([
                'name' => $name,
                'number' => $number,
                'bank_id' => $bank_id,
                'user_id' => $user_id,
                'status' => $status,
            ]);

            DB::commit();

            return $this->response(true, 'Berhasil mengubah data',$result);
        } catch (Throwable $th) {
            DB::rollback();
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->userBank->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
    
}
