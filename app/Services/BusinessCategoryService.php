<?php

namespace App\Services;

use App\Services\BaseService;
use App\Http\Requests\BusinessCategory\StoreRequest;
use App\Http\Requests\BusinessCategory\UpdateRequest;
use App\Models\BusinessCategory;
use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use Throwable;

class BusinessCategoryService extends BaseService
{
    protected $businessCategory;

    public function __construct()
    {
        $this->businessCategory = new BusinessCategory();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = $request->search;

        $table = $this->businessCategory;
        if (!empty($search)) {
            $table = $this->businessCategory->where(function ($query2) use ($search) {
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

    public function store(StoreRequest $request)
    {
        try {
            $create = $this->businessCategory->create([
                'name' => $request->name,
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
            $result = $this->businessCategory->findOrFail($id);

            $result->update([
                'name' => $request->name,
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
            $result = $this->businessCategory->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
