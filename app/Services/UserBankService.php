<?php

namespace App\Services;

use App\Services\BaseService;
use App\Http\Requests\UserBank\StoreRequest;
use App\Http\Requests\UserBank\UpdateRequest;
use App\Models\UserBank;
use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use Throwable;

class UserBankService extends BaseService
{
    protected $userBank;

    public function __construct()
    {
        $this->userBank = new UserBank();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = $request->search;
        $user_id = $request->user_id;

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
            $result = $result->findOrFail($id);

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
        try {
            $name = $request->name;
            $number = $request->number;
            $bank_id = $request->bank_id;
            $user_id = $request->user_id;

            $create = $this->userBank->create([
                'name' => $name,
                'number' => $number,
                'bank_id' => $bank_id,
                'user_id' => $user_id,
            ]);

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
            $number = $request->number;
            $bank_id = $request->bank_id;
            $user_id = $request->user_id;

            $result = $this->userBank->findOrFail($id);

            $result->update([
                'name' => $name,
                'number' => $number,
                'bank_id' => $bank_id,
                'user_id' => $user_id,
            ]);

            return $this->response(true, 'Berhasil mengubah data',$result);
        } catch (Throwable $th) {
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
