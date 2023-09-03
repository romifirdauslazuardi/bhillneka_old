<?php

namespace App\Services;

use App\Services\BaseService;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use Throwable;

class IndonesiaService extends BaseService
{
    protected $province;
    protected $city;
    protected $district;
    protected $village;

    public function __construct()
    {
        $this->province = new Province();
        $this->city = new City();
        $this->district = new District();
        $this->village = new Village();
    }

    public function province($request)
    {
        try {
            $id = $request->id;

            $table = $this->province;
            if($id){
                $table = $table->where("id",$id);
            }
            $table = $table->get();

            return $this->response(true, "Data berhasil didapatkan",$table);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function city($request)
    {
        try {
            $id = $request->id;
            $province_code = $request->province_code;

            $table = $this->city;
            if($id){
                $table = $table->where("id",$id);
            }
            if($province_code){
                $table = $table->where("province_code",$province_code);
            }
            $table = $table->get();

            return $this->response(true, "Data berhasil didapatkan",$table);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function district($request)
    {
        try {
            $id = $request->id;
            $city_code = $request->city_code;

            $table = $this->district;
            if($id){
                $table = $table->where("id",$id);
            }
            if($city_code){
                $table = $table->where("city_code",$city_code);
            }
            $table = $table->get();

            return $this->response(true, "Data berhasil didapatkan",$table);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function village($request)
    {
        try {
            $id = $request->id;
            $district_code = $request->district_code;

            $table = $this->village;
            if($id){
                $table = $table->where("id",$id);
            }
            if($district_code){
                $table = $table->where("district_code",$district_code);
            }
            $table = $table->get();

            return $this->response(true, "Data berhasil didapatkan",$table);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
