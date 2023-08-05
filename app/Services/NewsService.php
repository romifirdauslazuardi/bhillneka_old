<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Helpers\WhatsappHelper;
use App\Services\BaseService;
use App\Http\Requests\News\StoreRequest;
use App\Http\Requests\News\UpdateRequest;
use App\Models\News;
use App\Models\NewsRecipient;
use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use Throwable;

class NewsService extends BaseService
{
    protected $news;
    protected $newsRecipient;

    public function __construct()
    {
        $this->news = new News();
        $this->newsRecipient = new NewsRecipient();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));
        $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
        $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));

        if(!empty(Auth::user()->business_id)){
            $business_id = Auth::user()->business_id;
        }

        $table = $this->news;
        if (!empty($search)) {
            $table = $this->news->where(function ($query2) use ($search) {
                $query2->where('title', 'like', '%' . $search . '%');
                $query2->orWhere('note', 'like', '%' . $search . '%');
            });
        }
        if(!empty($user_id)){
            $table = $table->where("user_id",$user_id);
        }
        if(!empty($business_id)){
            $table = $table->where("business_id",$business_id);
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
        DB::beginTransaction();
        try {
            $title = (empty($request->title)) ? null : trim(strip_tags($request->title));
            $note = (empty($request->note)) ? null : trim(strip_tags($request->note));
            $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
            $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));
            $customer_id = $request->customer_id;

            if(Auth::user()->hasRole([RoleEnum::AGEN,RoleEnum::ADMIN_AGEN])){
                
                $check5xSend = $this->news;
                $check5xSend = $check5xSend->whereDate("created_at",">=",date("Y-m")."-1");
                $check5xSend = $check5xSend->whereDate("created_at","<=",date("Y-m-t"));
                $check5xSend = $check5xSend->where("business_id",$business_id);
                $check5xSend = $check5xSend->count();

                if($check5xSend >= 5){
                    DB::rollBack();
                    return $this->response(false,'Anda mencapai limit pengiriman pesan. Maksimal pengiriman pesan 5x dalam satu bulan');
                }
            }

            $create = $this->news->create([
                'title' => $title,
                'note' => $note,
                'user_id' => $user_id,
                'business_id' => $business_id,
                'author_id' => Auth::user()->id,
            ]);

            if(!empty($customer_id) && is_array($customer_id) && count($customer_id) >= 1){
                foreach($customer_id as $index => $row){
                    $recipient = $this->newsRecipient->updateOrCreate([
                        'news_id' => $create->id,
                        'user_id' => $row,
                    ],[
                        'news_id' => $create->id,
                        'user_id' => $row,
                        'author_id' => Auth::user()->id,
                    ]);

                    $message = $create->note;

                    WhatsappHelper::send($recipient->user->phone ?? null,$recipient->user->name ?? null,["title" => $create->title ,"message" => $message],true);
                }
            }

            DB::commit();

            return $this->response(true, 'Berhasil menambahkan data',$create);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $title = (empty($request->title)) ? null : trim(strip_tags($request->title));
            $note = (empty($request->note)) ? null : trim(strip_tags($request->note));
            $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
            $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));
            $customer_id = $request->customer_id;

            $result = $this->news->findOrFail($id);

            $result->update([
                'title' => $title,
                'note' => $note,
                'business_id' => $business_id,
                'user_id' => $user_id,
            ]);

            if(!empty($customer_id) && is_array($customer_id) && count($customer_id) >= 1){
                foreach($customer_id as $index => $row){
                    $recipient = $this->newsRecipient->updateOrCreate([
                        'news_id' => $result->id,
                        'user_id' => $row,
                    ],[
                        'news_id' => $result->id,
                        'user_id' => $row,
                        'author_id' => Auth::user()->id,
                    ]);

                    WhatsappHelper::send($recipient->user->phone ?? null,$recipient->user->name ?? null,["title" => $result->title ,"message" => $result->note],false);
                }
            }

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
}
