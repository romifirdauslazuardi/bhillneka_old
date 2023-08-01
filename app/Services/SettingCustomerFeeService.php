<?php

namespace App\Services;

use App\Services\BaseService;
use App\Http\Requests\Setting\SettingCustomerFee\StoreRequest;
use App\Http\Requests\Setting\SettingCustomerFee\UpdateRequest;
use App\Models\SettingCustomerFee;
use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use Throwable;

class SettingCustomerFeeService extends BaseService
{
    protected $settingCustomerFee;

    public function __construct()
    {
        $this->settingCustomerFee = new SettingCustomerFee();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));
        $mark = (empty($request->mark)) ? null : trim($request->mark);
        $type = (empty($request->type)) ? null : trim(strip_tags($request->type));

        $table = $this->settingCustomerFee;
        if (!empty($search)) {
            $table = $this->settingCustomerFee->where(function ($query2) use ($search) {
                $query2->where('mark', 'like', '%' . $search . '%');
                $query2->orWhere('limit', 'like', '%' . $search . '%');
                $query2->orWhere('type', 'like', '%' . $search . '%');
                $query2->orWhere('value', 'like', '%' . $search . '%');
            });
        }
        if(!empty($mark)){
            $table = $table->where("mark",$mark);
        }
        if(!empty($type)){
            $table = $table->where("type",$type);
        }

        if ($paginate) {
            $table = $table->orderBy('created_at', 'DESC');
            $table = $table->paginate(10);
            $table = $table->withQueryString();
        } else {
            $table = $table->orderBy('created_at', 'ASC');
            $table = $table->get();
        }

        return $this->response(true, 'Berhasil mendapatkan data', $table);
    }

    public function show($id)
    {
        try {
            $result = $this->settingCustomerFee;
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
            $mark = (empty($request->mark)) ? null : trim($request->mark);
            $limit = (empty($request->limit)) ? null : trim(strip_tags($request->limit));
            $type = (empty($request->type)) ? null : trim(strip_tags($request->type));
            $value = (empty($request->value)) ? null : trim(strip_tags($request->value));

            $checkExistFee = $this->settingCustomerFee;
            $checkExistFee = $checkExistFee->where("mark",$mark);
            $checkExistFee = $checkExistFee->where("limit",$limit);
            $checkExistFee = $checkExistFee->first();

            if($checkExistFee){
                return $this->response(false,"Fee sudah tersedia");
            }

            $create = $this->settingCustomerFee->create([
                'mark' => $mark,
                'limit' => $limit,
                'type' => $type,
                'value' => $value,
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
            $mark = (empty($request->mark)) ? null : trim($request->mark);
            $limit = (empty($request->limit)) ? null : trim(strip_tags($request->limit));
            $type = (empty($request->type)) ? null : trim(strip_tags($request->type));
            $value = (empty($request->value)) ? null : trim(strip_tags($request->value));

            $result = $this->settingCustomerFee->findOrFail($id);

            $checkExistFee = $this->settingCustomerFee;
            $checkExistFee = $checkExistFee->where("id","!=",$result->id);
            $checkExistFee = $checkExistFee->where("mark",$mark);
            $checkExistFee = $checkExistFee->where("limit",$limit);
            $checkExistFee = $checkExistFee->first();

            if($checkExistFee){
                return $this->response(false,"Sudah sudah tersedia");
            }

            $result->update([
                'mark' => $mark,
                'limit' => $limit,
                'type' => $type,
                'value' => $value,
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
            $result = $this->settingCustomerFee->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
