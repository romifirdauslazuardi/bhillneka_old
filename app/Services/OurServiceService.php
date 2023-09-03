<?php

namespace App\Services;

use App\Services\BaseService;
use App\Http\Requests\OurService\StoreRequest;
use App\Http\Requests\OurService\UpdateRequest;
use App\Models\OurService;
use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use Throwable;

class OurServiceService extends BaseService
{
    protected $ourService;

    public function __construct()
    {
        $this->ourService = new OurService();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));

        $table = $this->ourService;
        if (!empty($search)) {
            $table = $this->ourService->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%');
            });
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
            $result = $this->ourService;
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
            $description = (empty($request->description)) ? null : trim(strip_tags($request->description));
            $icon = (empty($request->icon)) ? null : trim(strip_tags($request->icon));
            
            $create = $this->ourService->create([
                'name' => $name,
                'description' => $description,
                'icon' => $icon,
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
            $description = (empty($request->description)) ? null : trim(strip_tags($request->description));
            $icon = (empty($request->icon)) ? null : trim(strip_tags($request->icon));

            $result = $this->ourService->findOrFail($id);

            $result->update([
                'name' => $name,
                'description' => $description,
                'icon' => $icon,
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
            $result = $this->ourService->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
