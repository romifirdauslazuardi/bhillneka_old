<?php

namespace App\Services;

use App\Enums\ProviderEnum;
use App\Services\BaseService;
use App\Http\Requests\Provider\StoreRequest;
use App\Http\Requests\Provider\UpdateRequest;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
use DB;
use Log;
use Throwable;

class ProviderService extends BaseService
{
    protected $provider;

    public function __construct()
    {
        $this->provider = new Provider();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));
        $status = (!isset($request->status)) ? null : trim(strip_tags($request->status));

        $table = $this->provider;
        if (!empty($search)) {
            $table = $this->provider->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%');
                $query2->orWhere('note', 'like', '%' . $search . '%');
            });
        }
        if(isset($status)){
            $table = $table->where("status",$status);
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
            $result = $this->provider;
            $result = $result->find($id);

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
            $client_id = (empty($request->client_id)) ? null : trim(strip_tags($request->client_id));
            $secret_key = (empty($request->secret_key)) ? null : trim(strip_tags($request->secret_key));
            $type = (empty($request->type)) ? null : trim(strip_tags($request->type));
            $status = (empty($request->status)) ? ProviderEnum::STATUS_FALSE : trim(strip_tags($request->status));
            $note = (empty($request->note)) ? null : trim(strip_tags($request->note));

            if($type == ProviderEnum::TYPE_DOKU){
                $checkExistDoku = $this->provider;
                $checkExistDoku = $checkExistDoku->where("type",ProviderEnum::TYPE_DOKU);
                $checkExistDoku = $checkExistDoku->first();

                if($checkExistDoku){
                    return $this->response(false, "Provider doku sudah ada",null,Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }

            $create = $this->provider->create([
                'name' => $name,
                'client_id' => $client_id,
                'secret_key' => $secret_key,
                'type' => $type,
                'status' => $status,
                'note' => $note,
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
            $client_id = (empty($request->client_id)) ? null : trim(strip_tags($request->client_id));
            $secret_key = (empty($request->secret_key)) ? null : trim(strip_tags($request->secret_key));
            $type = (empty($request->type)) ? null : trim(strip_tags($request->type));
            $status = (empty($request->status)) ? ProviderEnum::STATUS_FALSE : trim(strip_tags($request->status));
            $note = (empty($request->note)) ? null : trim(strip_tags($request->note));

            $result = $this->provider->findOrFail($id);

            if($type == ProviderEnum::TYPE_DOKU){
                $checkExistDoku = $this->provider;
                $checkExistDoku = $checkExistDoku->where("type",ProviderEnum::TYPE_DOKU);
                $checkExistDoku = $checkExistDoku->where("id","!=",$id);
                $checkExistDoku = $checkExistDoku->first();

                if($checkExistDoku){
                    return $this->response(false, "Provider doku sudah ada",null,Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }

            $result->update([
                'name' => $name,
                'client_id' => $client_id,
                'secret_key' => $secret_key,
                'type' => $type,
                'status' => $status,
                'note' => $note,
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
            $result = $this->provider->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
    
}
