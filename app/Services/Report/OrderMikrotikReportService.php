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

            $result = $this->orderMikrotik->findOrFail($id);

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
            ]);

            $mikrotikConfig = SettingHelper::mikrotikConfig();

            $connect = $this->routerosApi;
            $connect->debug("false");

            if(!$connect->connect($mikrotikConfig->ip,$mikrotikConfig->username,$mikrotikConfig->password,$mikrotikConfig->port)){
                DB::rollback();
                return $this->response(false, "Koneksi dengan mikrotik gagal. Silahkan cek konfigurasi anda");
            }

            if($result->type == OrderMikrotikEnum::TYPE_PPPOE){
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
}
