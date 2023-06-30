<?php

namespace App\Services;

use App\Services\BaseService;
use App\Http\Requests\Table\StoreRequest;
use App\Http\Requests\Table\UpdateRequest;
use App\Models\Table;
use Illuminate\Http\Request;
use App\Enums\RoleEnum;
use Auth;
use DB;
use Log;
use Throwable;

class TableService extends BaseService
{
    protected $table;

    public function __construct()
    {
        $this->table = new Table();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));
        $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
        $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));

        if(Auth::user()->hasRole([RoleEnum::AGEN])){
            $user_id = Auth::user()->id;
        }

        if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
            $user_id = Auth::user()->user_id;
        }

        if(!empty(Auth::user()->business_id)){
            $business_id = Auth::user()->business_id;
        }

        $table = $this->table;
        if (!empty($search)) {
            $table = $this->table->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%');
            });
        }
        if(!empty($user_id)){
            $table = $table->where("user_id",$user_id);
        }
        if(!empty($business_id)){
            $table = $table->where("business_id",$business_id);
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
            $result = $this->table;
            if(Auth::check()){
                if(Auth::user()->hasRole([RoleEnum::AGEN])){
                    $result = $result->where("user_id",Auth::user()->id);
                }
                if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
                    $result = $result->where("user_id",Auth::user()->user_id);
                }
                if(!empty(Auth::user()->business_id)){
                    $result = $result->where("business_id",Auth::user()->business_id);
                }
            }
            $result = $result->where('id',$id);
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
        try {
            $name = (empty($request->name)) ? null : trim(strip_tags($request->name));
            $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
            $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));

            $create = $this->table->create([
                'name' => $name,
                'user_id' => $user_id,
                'business_id' => $business_id,
                'author_id' => Auth::user()->id,
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
            $name = (empty($request->name)) ? null : trim(strip_tags($request->name));
            $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
            $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));

            $result = $this->table->findOrFail($id);

            $result->update([
                'name' => $name,
                'user_id' => $user_id,
                'business_id' => $business_id,
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
            $result = $this->table->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
    
}
