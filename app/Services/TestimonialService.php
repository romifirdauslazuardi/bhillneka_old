<?php

namespace App\Services;

use App\Enums\TestimonialEnum;
use App\Services\BaseService;
use App\Http\Requests\Testimonial\StoreRequest;
use App\Http\Requests\Testimonial\UpdateRequest;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Helpers\UploadHelper;
use Auth;
use DB;
use Log;
use Throwable;

class TestimonialService extends BaseService
{
    protected $testimonial;

    public function __construct()
    {
        $this->testimonial = new Testimonial();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));

        $table = $this->testimonial;
        if (!empty($search)) {
            $table = $this->testimonial->where(function ($query2) use ($search) {
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
            $result = $this->testimonial;
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
            $position = (empty($request->position)) ? null : trim(strip_tags($request->position));
            $message = (empty($request->message)) ? null : trim(strip_tags($request->message));
            $star = (empty($request->star)) ? null : trim(strip_tags($request->star));
            $avatar = $request->file("avatar");

            if ($avatar) {
                $upload = UploadHelper::upload_file($avatar, 'testimonials', TestimonialEnum::AVATAR_EXT);

                if ($upload["IsError"] == TRUE) {
                    return $this->response(false, $upload["Message"]);
                }

                $avatar = $upload["Path"];
            }

            $create = $this->testimonial->create([
                'name' => $name,
                'position' => $position,
                'message' => $message,
                'star' => $star,
                'avatar' => $avatar,
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
            $position = (empty($request->position)) ? null : trim(strip_tags($request->position));
            $message = (empty($request->message)) ? null : trim(strip_tags($request->message));
            $star = (empty($request->star)) ? null : trim(strip_tags($request->star));
            $avatar = $request->file("avatar");

            $result = $this->testimonial->findOrFail($id);

            if ($avatar) {
                $upload = UploadHelper::upload_file($avatar, 'testimonials', TestimonialEnum::AVATAR_EXT);

                if ($upload["IsError"] == TRUE) {
                    return $this->response(false, $upload["Message"]);
                }

                $avatar = $upload["Path"];
            }
            else{
                $avatar = $result->avatar;
            }

            $result->update([
                'name' => $name,
                'position' => $position,
                'message' => $message,
                'star' => $star,
                'avatar' => $avatar,
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
            $result = $this->testimonial->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
