<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Helpers\SlugHelper;
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
        $search = (empty($request->search)) ? null : trim(strip_tags($request->search));
        $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));

        if(Auth::user()->hasRole([RoleEnum::AGEN])){
            $user_id = Auth::user()->id;
        }

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
        if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
            $table = $table->where("user_id",Auth::user()->user_id);
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
            if(Auth::check()){
                if(Auth::user()->hasRole([RoleEnum::AGEN])){
                    $result = $result->where("user_id",Auth::user()->id);
                }
                if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
                    $result = $result->where("user_id",Auth::user()->user_id);
                }
            }
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

    public function showBySlug($slug)
    {
        try {
            $result = $this->business;
            if(Auth::check()){
                if(Auth::user()->hasRole([RoleEnum::AGEN])){
                    $result = $result->where("user_id",Auth::user()->id);
                }
                if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
                    $result = $result->where("user_id",Auth::user()->user_id);
                }
            }
            $result = $result->where('slug',$slug);
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
            $location = (empty($request->location)) ? null : trim(strip_tags($request->location));
            $description = (empty($request->description)) ? null : trim(strip_tags($request->description));
            $category_id = (empty($request->category_id)) ? null : trim(strip_tags($request->category_id));
            $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
            $village_code = (empty($request->village_code)) ? null : trim(strip_tags($request->village_code));

            $slug = SlugHelper::generate(Business::class,$name,'slug');

            $create = $this->business->create([
                'name' => $name,
                'slug' => $slug,
                'location' => $location,
                'description' => $description,
                'category_id' => $category_id,
                'user_id' => $user_id,
                'village_code' => $village_code,
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
            $location = (empty($request->location)) ? null : trim(strip_tags($request->location));
            $description = (empty($request->description)) ? null : trim(strip_tags($request->description));
            $category_id = (empty($request->category_id)) ? null : trim(strip_tags($request->category_id));
            $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
            $village_code = (empty($request->village_code)) ? null : trim(strip_tags($request->village_code));

            $result = $this->business->findOrFail($id);

            if($name != $result->name){
                $slug = SlugHelper::generate(Business::class,$name,'slug');
            }
            else{
                $slug = $result->slug;
            }

            $result->update([
                'name' => $name,
                'slug' => $slug,
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
