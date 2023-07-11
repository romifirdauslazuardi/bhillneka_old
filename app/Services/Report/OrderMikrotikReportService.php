<?php

namespace App\Services\Report;

use App\Enums\OrderEnum;
use App\Enums\OrderMikrotikEnum;
use App\Helpers\LogHelper;
use App\Helpers\SettingHelper;
use App\Http\Requests\Report\UpdateOrderMikrotikRequest;
use App\Services\BaseService;
use App\Models\OrderMikrotik;
use App\Models\RouterosAPI;
use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use Throwable;

class OrderMikrotikReportService extends BaseService
{
    protected $orderMikrotik;
    protected $routerosApi;

    public function __construct()
    {
        $this->orderMikrotik = new OrderMikrotik();
        $this->routerosApi = new RouterosAPI();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));
        $disabled = (empty($request->disabled)) ? null : trim(strip_tags($request->disabled));
        $type = (empty($request->type)) ? null : trim(strip_tags($request->type));

        $table = $this->orderMikrotik;
        if (!empty($search)) {
            $table = $this->orderMikrotik->where(function ($query2) use ($search) {
                $query2->where('username', 'like', '%' . $search . '%');
                $query2->orWhere('password', 'like', '%' . $search . '%');
                $query2->orWhere('local_address', 'like', '%' . $search . '%');
                $query2->orWhere('remote_address', 'like', '%' . $search . '%');
            });
        }
        $table = $table->whereHas("order_item",function($query2){
            $query2->whereHas("order",function($query3){
                $query3->where("status",OrderEnum::STATUS_SUCCESS);
            });
        });
        if(!empty($disabled)){
            $table = $table->where("disabled",$disabled);   
        }
        if(!empty($type)){
            $table = $table->where("type",$type);   
        }
        $table = $table->orderBy('created_at', 'DESC');

        if ($paginate) {
            $table = $table->paginate(10);
            $table = $table->withQueryString();
        } else {
            $table = $table->get();
        }

        foreach($table as $index => $row){
            $row->disabled_mikrotik = "Data tidak ditemukan didatabase/mikrotik";

            if(!empty($row->mikrotik_id)){
                $mikrotikConfig = SettingHelper::mikrotikConfig($row->order_item->order->business_id,$row->order_item->order->business->user_id);
                $ip = $mikrotikConfig->ip ?? null;
                $username = $mikrotikConfig->username ?? null;
                $password = $mikrotikConfig->password ?? null;
                $port = $mikrotikConfig->port ?? null;
                
                $connect = $this->routerosApi;
                $connect->debug("false");

                if($connect->connect($ip,$username,$password,$port)){
                    if($row->type == OrderMikrotikEnum::TYPE_PPPOE){
                        $connect = $connect->comm('/ppp/secret/print',[
                            '?.id' => $row->mikrotik_id
                        ]);
                    }
                    else{
                        $connect = $connect->comm('/ip/hotspot/user/print',[
                            '?.id' => $row->mikrotik_id
                        ]);
                    }

                    if(isset($connect[0]["disabled"])){
                        if($connect[0]["disabled"] == "false"){
                            $row->disabled_mikrotik = "no";
                        }
                        else if($connect[0]["disabled"] == "true"){
                            $row->disabled_mikrotik = "yes";
                        }
                    }
                }
            }
        }

        return $this->response(true, 'Berhasil mendapatkan data', $table);
    }

    public function show($id)
    {
        try {
            $result = $this->orderMikrotik;
            $result = $result->where('id',$id);
            $result = $result->first();

            if(!$result){
                return $this->response(false, "Data tidak ditemukan");
            }

            $result->disabled_mikrotik = "Data tidak ditemukan didatabase/mikrotik";

            if(!empty($result->mikrotik_id)){
                $mikrotikConfig = SettingHelper::mikrotikConfig($result->order_item->order->business_id,$result->order_item->order->business->user_id);
                $ip = $mikrotikConfig->ip ?? null;
                $username = $mikrotikConfig->username ?? null;
                $password = $mikrotikConfig->password ?? null;
                $port = $mikrotikConfig->port ?? null;
                
                $connect = $this->routerosApi;
                $connect->debug("false");
                
                if(!$connect->connect($ip,$username,$password,$port)){
                    return $this->response(false,'Koneksi dengan mikrotik gagal. Silahkan cek konfigurasi anda');
                }

                if($result->type == OrderMikrotikEnum::TYPE_PPPOE){
                    $connect = $connect->comm('/ppp/secret/print',[
                        '?.id' => $result->mikrotik_id
                    ]);
                }
                else{
                    $connect = $connect->comm('/ip/hotspot/user/print',[
                        '?.id' => $result->mikrotik_id
                    ]);
                }

                $connectLog = LogHelper::mikrotikLog($connect);

                if(isset($connect[0]["disabled"])){
                    if($connect[0]["disabled"] == "false"){
                        $result->disabled_mikrotik = "no";
                    }
                    else if($connect[0]["disabled"] == "true"){
                        $result->disabled_mikrotik = "yes";
                    }
                }
            }

            return $this->response(true, 'Berhasil mendapatkan data', $result);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function update(UpdateOrderMikrotikRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $username = (empty($request->username)) ? null : trim(strip_tags($request->username));
            $password = (empty($request->password)) ? null : trim(strip_tags($request->password));
            $profile = (empty($request->profile)) ? null : trim(strip_tags($request->profile));
            $server = (empty($request->input("server"))) ? null : trim(strip_tags($request->input("server")));
            $service = (empty($request->service)) ? null : trim(strip_tags($request->service));
            $local_address = (empty($request->local_address)) ? null : trim(strip_tags($request->local_address));
            $remote_address = (empty($request->remote_address)) ? null : trim(strip_tags($request->remote_address));
            $time_limit = (empty($request->time_limit)) ? null : trim(strip_tags($request->time_limit));
            $comment = (empty($request->comment)) ? null : trim(strip_tags($request->comment));
            $disabled = (empty($request->disabled)) ? null : trim(strip_tags($request->disabled));
            $address = (empty($request->address)) ? null : trim(strip_tags($request->address));
            $mac_address = (empty($request->mac_address)) ? null : trim(strip_tags($request->mac_address));
            $expired_date = (empty($request->expired_date)) ? null : trim(strip_tags($request->expired_date));

            $result = $this->orderMikrotik->findOrFail($id);

            $oldUsername = $result->username;

            $result->update([
                'username' => $username,
                'password' => $password,
                'profile' => $profile,
                'server' => $server,
                'service' => $service,
                'local_address' => $local_address,
                'remote_address' => $remote_address,
                'time_limit' => $time_limit,
                'comment' => $comment,
                'disabled' => $disabled,
                'address' => $address,
                'mac-address' => $mac_address,
                'expired_date' => $expired_date,
            ]);

            $mikrotikConfig = SettingHelper::mikrotikConfig($result->order_item->order->business_id ?? null,$result->order_item->order->business->user_id ?? null);
            $ipConfig = $mikrotikConfig->ip ?? null;
            $usernameConfig = $mikrotikConfig->username ?? null;
            $passwordConfig = $mikrotikConfig->password ?? null;
            $portConfig = $mikrotikConfig->port ?? null;

            $connect = $this->routerosApi;
            $connect->debug("false");

            if(!$connect->connect($ipConfig,$usernameConfig,$passwordConfig,$portConfig)){
                DB::rollback();
                return $this->response(false, "Koneksi dengan mikrotik gagal. Silahkan cek konfigurasi anda");
            }

            if($result->type == OrderMikrotikEnum::TYPE_PPPOE){
                
                if($oldUsername != $username){
                    $checkExistMikrotik = $connect->comm('/ppp/secret/print');

                    $connectLog = LogHelper::mikrotikLog($checkExistMikrotik);

                    if($connectLog["IsError"] == TRUE){
                        return $this->response(false, $connectLog["Message"]);
                    }

                    foreach($checkExistMikrotik as $i => $v){
                        if($v["name"] == $username){
                            return $this->response(false, "Username ". $username. " sudah terdaftar dimikrotik");
                        }
                    }
                }
                else{
                    $checkExistMikrotik = $connect->comm('/ppp/secret/print');

                    Log::info($checkExistMikrotik);

                    $connectLog = LogHelper::mikrotikLog($checkExistMikrotik);

                    if($connectLog["IsError"] == TRUE){
                        return $this->response(false, $connectLog["Message"]);
                    }

                    $checkExistMikrotikNumber = false;
                    foreach($checkExistMikrotik as $i => $v){
                        if($v["name"] == $username){
                           $checkExistMikrotikNumber = true;
                        }
                    }

                    if($checkExistMikrotikNumber == false){
                        return $this->response(false, 'Username '.$username." tidak ditemukan pada mikrotik");
                    }
                }

                $connectData = [
                    '.id' => $result->mikrotik_id,
                    'name' => $username,
                    'password' => $password,
                    'service' => $service,
                    'profile' => $profile,
                    'local-address' => $local_address,
                    'remote-address' => $remote_address,
                    'comment' => $comment,
                    'disabled' => $disabled,
                ];
                $connect = $connect->comm('/ppp/secret/set',$connectData);
            }
            else{
                if($oldUsername != $username){
                    $checkExistMikrotik = $connect->comm('/ip/hotspot/user/print');

                    Log::info($checkExistMikrotik);

                    $connectLog = LogHelper::mikrotikLog($checkExistMikrotik);

                    if($connectLog["IsError"] == TRUE){
                        return $this->response(false, $connectLog["Message"]);
                    }

                    foreach($checkExistMikrotik as $i => $v){
                        if($v["name"] == $username){
                            return $this->response(false, "Username ". $username. " sudah terdaftar dimikrotik");
                        }
                    }
                }else{
                    $checkExistMikrotik = $connect->comm('/ip/hotspot/user/print');

                    Log::info($checkExistMikrotik);

                    $connectLog = LogHelper::mikrotikLog($checkExistMikrotik);

                    if($connectLog["IsError"] == TRUE){
                        return $this->response(false, $connectLog["Message"]);
                    }

                    $checkExistMikrotikNumber = false;
                    foreach($checkExistMikrotik as $i => $v){
                        if($v["name"] == $username){
                           $checkExistMikrotikNumber = true;
                        }
                    }

                    if($checkExistMikrotikNumber == false){
                        return $this->response(false, 'Username '.$username." tidak ditemukan pada mikrotik");
                    }
                }
                
                $connectData = [
                    '.id' => $result->mikrotik_id,
                    'name' => $username,
                    'password' => $password,
                    'server' => $server,
                    'profile' => $profile,
                    'limit-uptime' => $time_limit,
                    'comment' => $comment,
                    'disabled' => $disabled,
                ];

                if(!empty($address)){
                    $connectData = array_merge($connectData,["address" => $address]);
                }

                if(!empty($mac_address)){
                    $connectData = array_merge($connectData,["mac-address" => $mac_address]);
                }

                $connect = $connect->comm('/ip/hotspot/user/set',$connectData);
            }

            $connectLog = LogHelper::mikrotikLog($connect);

            if($connectLog["IsError"] == TRUE){
                DB::rollback();
                return $this->response(false, $connectLog["Message"]);
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
            $result = $this->orderMikrotik->findOrFail($id);
            
            $mikrotikConfig = SettingHelper::mikrotikConfig($result->order_item->order->business_id ?? null,$result->order_item->order->business->user_id ?? null);
            $ip = $mikrotikConfig->ip ?? null;
            $username = $mikrotikConfig->username ?? null;
            $password = $mikrotikConfig->password ?? null;
            $port = $mikrotikConfig->port ?? null;

            $connect = $this->routerosApi;
            $connect->debug("false");

            if(!$connect->connect($ip,$username,$password,$port)){
                return $this->response(false, "Koneksi dengan mikrotik gagal. Silahkan cek konfigurasi anda");
            }

            if(empty($result->mikrotik_id)){
                return $this->response(false, "Data mikrotik belum tersimpan kedatabase");
            }else{
                if($result->type == OrderMikrotikEnum::TYPE_PPPOE){
                    $connect = $connect->comm('/ppp/secret/remove',[
                        '.id' => $result->mikrotik_id
                    ]);
                }
                else{
                    $connect = $connect->comm('/ip/hotspot/user/remove',[
                        '.id' => $result->mikrotik_id
                    ]);
                }
    
                $connectLog = LogHelper::mikrotikLog($connect);
    
                if($connectLog["IsError"] == TRUE){
                    return $this->response(false, $connectLog["Message"]);
                }
    
                $result->update([
                    'mikrotik_id' => null,
                ]);
            }

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
