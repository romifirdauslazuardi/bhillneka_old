<?php

namespace App\Services;

use App\Services\BaseService;
use App\Http\Requests\Setting\SettingFeeRequest;
use App\Models\SettingFee;
use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Http\Response;
use Log;
use Throwable;

class SettingFeeService extends BaseService
{
    protected $settingFee;

    public function __construct()
    {
        $this->settingFee = new SettingFee();
    }

    public function index()
    {
        try {
            $result = $this->settingFee;
            $result = $result->orderBy("created_at","DESC");
            $result = $result->first();

            return $this->response(true, 'Berhasil mendapatkan data', $result);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function update(SettingFeeRequest $request)
    {
        try {
            $owner_fee = $request->owner_fee;
            $agen_fee = $request->agen_fee;

            $result = $this->settingFee;
            $result = $result->orderBy("created_at","DESC");
            $result = $result->first();

            $total = $owner_fee + $agen_fee;

            if($total != 100){
                return $this->response(false, "Jumlah fee owner + fee agen harus 100%");
            }

            if($result){
                $result->update([
                    'owner_fee' => $owner_fee,
                    'agen_fee' => $agen_fee,
                ]);
            }
            else{
                $this->settingFee->create([
                    'owner_fee' => $owner_fee,
                    'agen_fee' => $agen_fee,
                ]);
            }

            return $this->response(true, "Data berhasil diubah");
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
