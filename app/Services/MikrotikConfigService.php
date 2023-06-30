<?php

namespace App\Services;

use App\Services\BaseService;
use App\Http\Requests\MikrotikConfig\StoreRequest;
use App\Http\Requests\MikrotikConfig\UpdateRequest;
use App\Models\MikrotikConfig;
use App\Models\RouterosAPI;
use Illuminate\Http\Request;
use App\Enums\RoleEnum;
use App\Helpers\SettingHelper;
use Auth;
use DB;
use Log;
use Throwable;

class MikrotikConfigService extends BaseService
{
    protected $mikrotikConfig;
    protected $routerosApi;

    public function __construct()
    {
        $this->mikrotikConfig = new MikrotikConfig();
        $this->routerosApi = new RouterosAPI();
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

        $table = $this->mikrotikConfig;
        if (!empty($search)) {
            $table = $this->mikrotikConfig->where(function ($query2) use ($search) {
                $query2->where('ip', 'like', '%' . $search . '%');
                $query2->orWhere('username', 'like', '%' . $search . '%');
                $query2->orWhere('password', 'like', '%' . $search . '%');
            });
        }
        if(!empty($user_id)){
            $table = $table->where("user_id",$user_id);
        }
        if(!empty($business_id)){
            $table = $table->where("business_id",$business_id);
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
            $result = $this->mikrotikConfig;
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

            return $this->response(true, 'Berhasil mendapatkan data', $result);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            $ip = (empty($request->ip)) ? null : trim(strip_tags($request->ip));
            $username = (empty($request->username)) ? null : trim(strip_tags($request->username));
            $password = (empty($request->password)) ? null : trim(strip_tags($request->password));
            $port = (empty($request->port)) ? null : trim(strip_tags($request->port));
            $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
            $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));

            $checkExist = $this->mikrotikConfig;
            $checkExist = $checkExist->where("user_id",$user_id);
            $checkExist = $checkExist->where("business_id",$business_id);
            $checkExist = $checkExist->first();

            if($checkExist){
                return $this->response(false, "Tidak dapat menambahkan lebih dari 1 konfigurasi pada bisnis yang sama");
            }

            $connect = $this->routerosApi;
            $connect->debug("false");

            if(!$connect->connect($ip,$username,$password,$port)){
                return $this->response(false, "Koneksi dengan mikrotik gagal. Silahkan cek konfigurasi anda");
            }

            $create = $this->mikrotikConfig->updateOrCreate([
                'user_id' => $user_id,
                'business_id' => $business_id,
            ],[
                'ip' => $ip,
                'username' => $username,
                'password' => $password,
                'port' => $port,
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
            $ip = (empty($request->ip)) ? null : trim(strip_tags($request->ip));
            $username = (empty($request->username)) ? null : trim(strip_tags($request->username));
            $password = (empty($request->password)) ? null : trim(strip_tags($request->password));
            $port = (empty($request->port)) ? null : trim(strip_tags($request->port));
            $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
            $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));

            $result = $this->mikrotikConfig->findOrFail($id);

            $connect = $this->routerosApi;
            $connect->debug("false");

            if(!$connect->connect($ip,$username,$password,$port)){
                return $this->response(false, "Koneksi dengan mikrotik gagal. Silahkan cek konfigurasi anda");
            }

            $result->update([
                'ip' => $ip,
                'username' => $username,
                'password' => $password,
                'port' => $port,
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
            $result = $this->mikrotikConfig->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function profilePppoe(){
        try {
            $mikrotikConfig = SettingHelper::mikrotikConfig();
            $ip = $mikrotikConfig->ip ?? null;
            $username = $mikrotikConfig->username ?? null;
            $password = $mikrotikConfig->password ?? null;
            $port = $mikrotikConfig->port ?? null;

            if(!$ip || !$username || !$password || !$port){
                return $this->response(false, "Koneksi dengan mikrotik gagal. Silahkan cek konfigurasi anda");
            }

            $connect = $this->routerosApi;
            $connect->debug("false");

            if(!$connect->connect($ip,$username,$password,$port)){
                return $this->response(false, "Koneksi dengan mikrotik gagal. Silahkan cek konfigurasi anda");
            }

            $data = $connect->comm('/ppp/profile/print');

            return $this->response(true, 'Berhasil medapatkan data',$data);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function profileHotspot(){
        try {
            $mikrotikConfig = SettingHelper::mikrotikConfig();
            $ip = $mikrotikConfig->ip ?? null;
            $username = $mikrotikConfig->username ?? null;
            $password = $mikrotikConfig->password ?? null;
            $port = $mikrotikConfig->port ?? null;

            if(!$ip || !$username || !$password || !$port){
                return $this->response(false, "Koneksi dengan mikrotik gagal. Silahkan cek konfigurasi anda");
            }

            $connect = $this->routerosApi;
            $connect->debug("false");

            if(!$connect->connect($ip,$username,$password,$port)){
                return $this->response(false, "Koneksi dengan mikrotik gagal. Silahkan cek konfigurasi anda");
            }

            $data = $connect->comm('/ip/hotspot/profile/print');

            return $this->response(true, 'Berhasil medapatkan data',$data);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function serverHotspot(){
        try {
            $mikrotikConfig = SettingHelper::mikrotikConfig();
            $ip = $mikrotikConfig->ip ?? null;
            $username = $mikrotikConfig->username ?? null;
            $password = $mikrotikConfig->password ?? null;
            $port = $mikrotikConfig->port ?? null;

            if(!$ip || !$username || !$password || !$port){
                return $this->response(false, "Koneksi dengan mikrotik gagal. Silahkan cek konfigurasi anda");
            }

            $connect = $this->routerosApi;
            $connect->debug("false");

            if(!$connect->connect($ip,$username,$password,$port)){
                return $this->response(false, "Koneksi dengan mikrotik gagal. Silahkan cek konfigurasi anda");
            }

            $data = $connect->comm('/ip/hotspot/print');

            return $this->response(true, 'Berhasil medapatkan data',$data);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
    
}
