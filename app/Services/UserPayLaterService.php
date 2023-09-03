<?php

namespace App\Services;

use App\Enums\ProviderEnum;
use App\Services\BaseService;
use App\Http\Requests\UserPayLater\StoreRequest;
use App\Models\Business;
use App\Models\UserPayLater;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Enums\RoleEnum;
use App\Enums\UserPayLaterEnum;
use Auth;
use DB;
use Log;
use Throwable;

class UserPayLaterService extends BaseService
{
    protected $business;
    protected $userPayLater;
    protected $provider;

    public function __construct()
    {
        $this->business = new Business();
        $this->userPayLater = new UserPayLater();
        $this->provider = new Provider();
    }

    public function index(Request $request, bool $paginate = true)
    {
        $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
        $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));

        $provider = $this->provider;
        $provider = $provider->where("type",ProviderEnum::TYPE_PAY_LATER);
        $provider = $provider->where("status",ProviderEnum::STATUS_TRUE);
        $provider = $provider->first();

        if(!$provider){
            return $this->response(false, 'Metode pembayaran bayar nanti belum diaktifkan oleh owner');   
        }

        if(Auth::user()->hasRole([RoleEnum::AGEN])){
            $user_id = Auth::user()->id;
        }

        if(Auth::user()->hasRole([RoleEnum::ADMIN_AGEN])){
            $user_id = Auth::user()->user_id;
        }

        if(!empty(Auth::user()->business_id)){
            $business_id = Auth::user()->business_id;
        }

        $business = $this->business;
        if(!empty($user_id)){
            $business = $business->where("user_id",$user_id);
        }
        if(!empty($business_id)){
            $business = $business->where("id",$business_id);
        }
        $business = $business->orderBy('created_at', 'DESC');

        if ($paginate) {
            $business = $business->paginate(10);
            $business = $business->withQueryString();
        } else {
            $business = $business->get();
        }

        return $this->response(true, 'Berhasil mendapatkan data', $business);
    }

    public function store(StoreRequest $request)
    {
        try {
            $status = (empty($request->status)) ? UserPayLaterEnum::STATUS_FALSE : trim(strip_tags($request->status));
            $user_id = (empty($request->user_id)) ? null : trim(strip_tags($request->user_id));
            $business_id = (empty($request->business_id)) ? null : trim(strip_tags($request->business_id));

            $create = $this->userPayLater->updateOrCreate([
                'user_id' => $user_id,
                'business_id' => $business_id,
            ],[
                'status' => $status,
                'user_id' => $user_id,
                'business_id' => $business_id,
            ]);

            return $this->response(true, 'Berhasil mengubah data',$create);
        } catch (Throwable $th) {
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }
    
}
