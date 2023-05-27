<?php

namespace App\Services;

use App\Services\BaseService;
use App\Http\Requests\Business\StoreRequest;
use App\Http\Requests\Business\UpdateRequest;
use App\Models\Business;
use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use Throwable;

class BusinessService extends BaseService
{
    protected $business;

    public function __construct()
    {
        $this->business = new Business();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = $request->search;
        $user_id = $request->user_id;

        $table = $this->business;
        if (!empty($search)) {
            $table = $this->business->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%');
                $query2->orWhere('location', 'like', '%' . $search . '%');
                $query2->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        if(!empty($user_id)){
            $table = $table->where("user_id",$user_id);
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
            $result = $this->business;
            $result = $result->findOrFail($id);

            return $this->response(true, 'Berhasil mendapatkan data', $result);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            $name = $request->name;
            $location = $request->location;
            $description = $request->description;
            $category_id = $request->category_id;
            $user_id = $request->user_id;
            $village_code = $request->village_code;

            $create = $this->business->create([
                'name' => $name,
                'location' => $location,
                'description' => $description,
                'category_id' => $category_id,
                'user_id' => $user_id,
                'village_code' => $village_code,
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
            $name = $request->name;
            $location = $request->location;
            $description = $request->description;
            $category_id = $request->category_id;
            $user_id = $request->user_id;
            $village_code = $request->village_code;

            $result = $this->business->findOrFail($id);

            $result->update([
               'name' => $name,
                'location' => $location,
                'description' => $description,
                'category_id' => $category_id,
                'user_id' => $user_id,
                'village_code' => $village_code,
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
            $result = $this->business->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
    
}
