<?php

namespace App\Services;

use App\Enums\PartnerEnum;
use App\Helpers\UploadHelper;
use App\Services\BaseService;
use App\Http\Requests\Partner\StoreRequest;
use App\Http\Requests\Partner\UpdateRequest;
use App\Models\Partner;
use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use Throwable;

class PartnerService extends BaseService
{
    protected $partner;

    public function __construct()
    {
        $this->partner = new Partner();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));

        $table = $this->partner;
        if (!empty($search)) {
            $table = $this->partner->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%');
            });
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
            $result = $this->partner;
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
            $image = $request->file("image");

            if ($image) {
                $upload = UploadHelper::upload_file($image, 'partners', PartnerEnum::IMAGE_EXT);

                if ($upload["IsError"] == TRUE) {
                    return $this->response(false, $upload["Message"]);
                }

                $image = $upload["Path"];
            }

            $create = $this->partner->create([
                'name' => $name,
                'image' => $image,
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
            $image = $request->file("image");

            $result = $this->partner->findOrFail($id);

            if ($image) {
                $upload = UploadHelper::upload_file($image, 'partners', PartnerEnum::IMAGE_EXT);

                if ($upload["IsError"] == TRUE) {
                    return $this->response(false, $upload["Message"]);
                }

                $image = $upload["Path"];
            }
            else{
                $image = $result->image;
            }

            $result->update([
                'name' => $name,
                'image' => $image,
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
            $result = $this->partner->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
