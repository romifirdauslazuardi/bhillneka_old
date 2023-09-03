<?php

namespace App\Services;

use App\Enums\SettingFeeEnum;
use App\Services\BaseService;
use App\Http\Requests\Setting\SettingFee\StoreRequest;
use App\Http\Requests\Setting\SettingFee\UpdateRequest;
use App\Models\SettingFee;
use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use Throwable;

class SettingFeeService extends BaseService
{
    protected $settingFee;

    public function __construct()
    {
        $this->settingFee = new SettingFee();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));
        $mark = (empty($request->mark)) ? null : trim($request->mark);
        $service = (empty($request->service)) ? null : trim(strip_tags($request->service));

        $table = $this->settingFee;
        if (!empty($search)) {
            $table = $this->settingFee->where(function ($query2) use ($search) {
                $query2->where('mark', 'like', '%' . $search . '%');
                $query2->orWhere('limit', 'like', '%' . $search . '%');
                $query2->orWhere('owner_fee', 'like', '%' . $search . '%');
                $query2->orWhere('agen_fee', 'like', '%' . $search . '%');
            });
        }
        if(!empty($mark)){
            $table = $table->where("mark",$mark);
        }
        if(!empty($service)){
            $table = $table->where("service",$service);
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
            $result = $this->settingFee;
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
            $owner_fee = (!isset($request->owner_fee)) ? null : trim(strip_tags($request->owner_fee));

            $limit = str_replace(".","",$limit);

            $checkExistFee = $this->settingFee;
            $checkExistFee = $checkExistFee->where("mark",$mark);
            $checkExistFee = $checkExistFee->where("limit",$limit);
            $checkExistFee = $checkExistFee->first();

            if($checkExistFee){
                return $this->response(false,"Fee sudah tersedia");
            }

            $agen_fee = 100 - $owner_fee;

            if($owner_fee + $agen_fee >= 101){
                return $this->response(false,"Total fee owner dan agen maksimal 100%");
            }

            $create = $this->settingFee->create([
                'mark' => $mark,
                'limit' => $limit,
                'owner_fee' => $owner_fee,
                'agen_fee' => $agen_fee,
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
            $owner_fee = (empty($request->owner_fee)) ? null : trim(strip_tags($request->owner_fee));

            $limit = str_replace(".","",$limit);
            
            $result = $this->settingFee->findOrFail($id);

            $checkExistFee = $this->settingFee;
            $checkExistFee = $checkExistFee->where("id","!=",$result->id);
            $checkExistFee = $checkExistFee->where("mark",$mark);
            $checkExistFee = $checkExistFee->where("limit",$limit);
            $checkExistFee = $checkExistFee->first();

            if($checkExistFee){
                return $this->response(false,"Sudah sudah tersedia");
            }

            $agen_fee = 100 - $owner_fee;

            if($owner_fee + $agen_fee >= 101){
                return $this->response(false,"Total fee owner dan agen maksimal 100%");
            }

            $result->update([
                'mark' => $mark,
                'limit' => $limit,
                'owner_fee' => $owner_fee,
                'agen_fee' => $agen_fee,
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
            $result = $this->settingFee->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
