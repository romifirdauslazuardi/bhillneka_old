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
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));
        $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
        $status = (empty($request->status)) ? null : trim(strip_tags($request->status));

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

            $verified = $this->userBank;
            $verified = $verified->where("user_id",$result->user_id);
            $verified = $verified->where("id","!=",$result->id);
            $verified = $verified->orderBy("created_at","DESC");
            $verified = $verified->where("status",UserBankEnum::STATUS_APPROVED);
            $verified = $verified->get();

            $result->verified = $verified;

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
            $number = (empty($request->number)) ? null : trim(strip_tags($request->number));
            $bank_id = (empty($request->bank_id)) ? null : trim(strip_tags($request->bank_id));
            $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
            $status = (empty($request->status)) ? null : trim(strip_tags($request->status));
            $default = (empty($request->default)) ? UserBankEnum::DEFAULT_FALSE : trim(strip_tags($request->default));
            $bank_settlement_id = (empty($request->bank_settlement_id)) ? null : trim(strip_tags($request->bank_settlement_id));

            $create = $this->userBank->create([
                'name' => $name,
                'number' => $number,
                'bank_id' => $bank_id,
                'user_id' => $user_id,
                'status' => $status,
                'default' => $default,
                'bank_settlement_id' => $bank_settlement_id,
                'author_id' => Auth::user()->id,
            ]);

            if(Auth::user()->hasRole([RoleEnum::AGEN,RoleEnum::ADMIN_AGEN])){
                $owners = $this->user;
                $owners = $owners->role([RoleEnum::OWNER]);
                $owners = $owners->get();

                Notification::send($owners,new UserBankNotification(route('dashboard.user-banks.show',$create->id),"Pengajuan Rekening Baru","Terdapat pengajuan rekening baru dari ".Auth::user()->name.". Silahkan approve / reject pengajuan ini"));
            }

            if($create->default == UserBankEnum::DEFAULT_TRUE){
                $disabledOtherBank = $this->userBank;
                $disabledOtherBank = $disabledOtherBank->where("id","!=",$create->id);
                $disabledOtherBank = $disabledOtherBank->where("user_id",$create->user_id);
                $disabledOtherBank = $disabledOtherBank->get();

                foreach($disabledOtherBank as $index => $row){
                    $row->update([
                        'default' => UserBankEnum::DEFAULT_FALSE
                    ]);
                }
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
            $name = (empty($request->name)) ? null : trim(strip_tags($request->name));
            $number = (empty($request->number)) ? null : trim(strip_tags($request->number));
            $bank_id = (empty($request->bank_id)) ? null : trim(strip_tags($request->bank_id));
            $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
            $status = (empty($request->status)) ? null : trim(strip_tags($request->status));
            $default = (empty($request->default)) ? UserBankEnum::DEFAULT_FALSE : trim(strip_tags($request->default));
            $bank_settlement_id = (empty($request->bank_settlement_id)) ? null : trim(strip_tags($request->bank_settlement_id));

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
                'default' => $default,
                'bank_settlement_id' => $bank_settlement_id,
            ]);

            if($result->default == UserBankEnum::DEFAULT_TRUE){
                $disabledOtherBank = $this->userBank;
                $disabledOtherBank = $disabledOtherBank->where("id","!=",$result->id);
                $disabledOtherBank = $disabledOtherBank->where("user_id",$result->user_id);
                $disabledOtherBank = $disabledOtherBank->get();

                foreach($disabledOtherBank as $index => $row){
                    $row->update([
                        'default' => UserBankEnum::DEFAULT_FALSE
                    ]);
                }
            }

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
