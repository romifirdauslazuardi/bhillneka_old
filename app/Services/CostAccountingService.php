<?php

namespace App\Services;

use App\Enums\CostAccountingEnum;
use App\Http\Requests\CostAccounting\ImportRequest;
use App\Services\BaseService;
use App\Http\Requests\CostAccounting\StoreRequest;
use App\Http\Requests\CostAccounting\UpdateRequest;
use App\Jobs\CostAccountingJob;
use App\Models\CostAccounting;
use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use Throwable;

class CostAccountingService extends BaseService
{
    protected $news;

    public function __construct()
    {
        $this->news = new CostAccounting();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));
        $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
        $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));
        $type = (empty($request->type)) ? null : trim(strip_tags($request->type));
        $from_date = (empty($request->from_date)) ? null : trim(strip_tags($request->from_date));
        $to_date = (empty($request->to_date)) ? null : trim(strip_tags($request->to_date));

        $table = $this->news;
        if (!empty($search)) {
            $table = $this->news->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%');
                $query2->orWhere('description', 'like', '%' . $search . '%');
                $query2->orWhere('nominal', 'like', '%' . $search . '%');
            });
        }
        if(!empty($user_id)){
            $table = $table->where("user_id",$user_id);
        }
        if(!empty($business_id)){
            $table = $table->where("business_id",$business_id);
        }
        if(!empty($type)){
            $table = $table->where("type",$type);
        }
        if(!empty($from_date)){
            $table = $table->whereDate("created_at",">=",$from_date);
        }
        if(!empty($to_date)){
            $table = $table->whereDate("created_at","<=",$to_date);
        }

        if ($paginate) {
            $table = $table->orderBy('created_at', 'DESC');
            $table = $table->paginate(10);
            $table = $table->withQueryString();
        } else {
            $table = $table->orderBy('created_at', 'ASC');
            $table = $table->get();
        }

        return $this->response(true, 'Berhasil mendapatkan data', $table);
    }

    public function show($id)
    {
        try {
            $result = $this->news;
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
            $type = (empty($request->type)) ? null : trim(strip_tags($request->type));
            $name = (empty($request->name)) ? null : trim(strip_tags($request->name));
            $date = (empty($request->date)) ? null : trim(strip_tags($request->date));
            $description = (empty($request->description)) ? null : trim(strip_tags($request->description));
            $nominal = (empty($request->nominal)) ? null : trim(strip_tags($request->nominal));
            $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
            $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));

            $nominal = str_replace(".","",$nominal);

            $create = $this->news->create([
                'type' => $type,
                'name' => $name,
                'date' => $date,
                'description' => $description,
                'nominal' => $nominal,
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
            $type = (empty($request->type)) ? null : trim(strip_tags($request->type));
            $name = (empty($request->name)) ? null : trim(strip_tags($request->name));
            $date = (empty($request->date)) ? null : trim(strip_tags($request->date));
            $description = (empty($request->description)) ? null : trim(strip_tags($request->description));
            $nominal = (empty($request->nominal)) ? null : trim(strip_tags($request->nominal));
            $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
            $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));

            $result = $this->news->findOrFail($id);

            $nominal = str_replace(".","",$nominal);

            $result->update([
                'type' => $type,
                'name' => $name,
                'date' => $date,
                'description' => $description,
                'nominal' => $nominal,
                'user_id' => $user_id,
                'business_id' => $business_id,
            ]);

            return $this->response(true, 'Berhasil mengubah data',$result);
        } catch (Throwable $th) {
            DB::beginTransaction();
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->news->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function importExcel(ImportRequest $request)
    {
        try {
            $file = $request->file('file');

            $extension = $file->getClientOriginalExtension();
            $filename = time().'.'.$extension;
            $file->storeAs('public/import/cost-accountings', $filename);
            CostAccountingJob::dispatch($filename, Auth::user());

            return $this->response(true, 'Berhasil import data');
        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());

            return $this->response(false, $e->getMessage());
        }
    }
}
