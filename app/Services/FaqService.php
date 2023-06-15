<?php

namespace App\Services;

use App\Services\BaseService;
use App\Http\Requests\Faq\StoreRequest;
use App\Http\Requests\Faq\UpdateRequest;
use App\Models\Faq;
use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use Throwable;

class FaqService extends BaseService
{
    protected $faq;

    public function __construct()
    {
        $this->faq = new Faq();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));

        $table = $this->faq;
        if (!empty($search)) {
            $table = $this->faq->where(function ($query2) use ($search) {
                $query2->where('question', 'like', '%' . $search . '%');
                $query2->orWhere('answer', 'like', '%' . $search . '%');
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
            $question = (empty($request->question)) ? null : trim(strip_tags($request->question));
            $answer = (empty($request->answer)) ? null : trim(strip_tags($request->answer));

            $create = $this->faq->create([
                'question' => $question,
                'answer' => $answer,
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
            $question = (empty($request->question)) ? null : trim(strip_tags($request->question));
            $answer = (empty($request->answer)) ? null : trim(strip_tags($request->answer));

            $result = $this->faq->findOrFail($id);

            $result->update([
                'question' => $question,
                'answer' => $answer,
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
            $result = $this->faq->findOrFail($id);
            $result->delete();

            return $this->response(true, 'Berhasil menghapus data');
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
}
