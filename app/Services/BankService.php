<?php

namespace App\Services;

use App\Services\BaseService;
use App\Http\Requests\Bank\StoreRequest;
use App\Http\Requests\Bank\UpdateRequest;
use App\Models\Bank;
use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use Throwable;

class BankService extends BaseService
{
    protected $bank;

    public function __construct()
    {
        $this->bank = new Bank();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));

        $table = $this->bank;
        if (!empty($search)) {
            $table = $this->bank->where(function ($query2) use ($search) {
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
            $name = (empty($request->name)) ? null : trim(strip_tags($request->name));

            $create = $this->bank->create([
                'name' => $name,
                'author_id' => Auth::user()->id
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

            $result = $this->bank->findOrFail($id);

            $result->update([
                'name' => $name,
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
            $result = $this->bank->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
