<?php

namespace App\Services;

use App\Services\BaseService;
use App\Helpers\SlugHelper;
use App\Http\Requests\WhyUs\StoreRequest;
use App\Http\Requests\WhyUs\UpdateRequest;
use App\Models\WhyUs;
use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use Throwable;

class WhyUsService extends BaseService
{
    protected $whyUs;

    public function __construct()
    {
        $this->whyUs = new WhyUs();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));

        $table = $this->whyUs;
        if (!empty($search)) {
            $table = $this->whyUs->where(function ($query2) use ($search) {
                $query2->where('title', 'like', '%' . $search . '%');
                $query2->orWhere('sub_title', 'like', '%' . $search . '%');
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
            $result = $this->whyUs;
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
        DB::beginTransaction();
        try {
            $title = (empty($request->title)) ? null : trim(strip_tags($request->title));
            $sub_title = (empty($request->sub_title)) ? null : trim(strip_tags($request->sub_title));

            $create = $this->whyUs->create([
                'title' => $title,
                'sub_title' => $sub_title,
                'whyus-trixFields' => $request->input('whyus-trixFields'),
                'author_id' => Auth::user()->id,
            ]);

            DB::commit();

            return $this->response(true, 'Berhasil menambahkan data',$create);
        } catch (Throwable $th) {
            DB::rollback();
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $title = (empty($request->title)) ? null : trim(strip_tags($request->title));
            $sub_title = (empty($request->sub_title)) ? null : trim(strip_tags($request->sub_title));

            $result = $this->whyUs->findOrFail($id);

            $result->update([
                'title' => $title,
                'sub_title' => $sub_title,
                'whyus-trixFields' => $request->input('whyus-trixFields'),
            ]);

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
            $result = $this->whyUs->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
