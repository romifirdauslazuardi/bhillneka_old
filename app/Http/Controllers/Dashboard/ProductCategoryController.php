<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ProductEnum;
use App\Enums\RoleEnum;
use App\Helpers\ResponseHelper;
use App\Helpers\SettingHelper;
use App\Helpers\UploadHelper;
use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Auth;
use Log;
use Throwable;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ProductCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (empty(Auth::user()->business_id)) {
                if ($request->wantsJson()) {
                    return ResponseHelper::apiResponse(false, "Bisnis page belum diaktifkan");
                }
                alert()->error('Gagal', "Bisnis page belum diaktifkan");
                return redirect()->route("dashboard.index");
            }
            return $next($request);
        }, ['only' => ['create', 'edit', 'store', 'destroy']]);

        $this->middleware(function ($request, $next) {
            if (
                Auth::user()->hasRole([
                    RoleEnum::AGEN,
                    RoleEnum::ADMIN_AGEN
                ])
                && empty(Auth::user()->business_id)
            ) {
                if ($request->wantsJson()) {
                    return ResponseHelper::apiResponse(false, "Bisnis page belum diaktifkan");
                }
                alert()->error('Gagal', "Bisnis page belum diaktifkan");
                return redirect()->route("dashboard.index");
            }
            return $next($request);
        }, ['only' => ['index', 'show']]);

        $this->middleware(function ($request, $next) {
            if (SettingHelper::hasBankActive() == false) {
                if ($request->wantsJson()) {
                    return ResponseHelper::apiResponse(false, "Tidak ada rekening bank anda yang sudah diverifikasi oleh owner");
                }
                alert()->error('Gagal', "Tidak ada rekening bank anda yang sudah diverifikasi oleh owner");
                return redirect()->route("dashboard.index");
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ProductCategory::where('business_id', auth()->user()->business_id)->paginate(10);
        return view('dashboard.product-categories.index', [
            "data" => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.product-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = new ProductCategory();
            $image = $request->image;
            if ($image) {
                $upload = UploadHelper::upload_file($image, 'product_categories', ProductEnum::IMAGE_EXT);

                if ($upload["IsError"] == TRUE) {
                    return $this->response(false, $upload["Message"]);
                }

                $image = $upload["Path"];
            }
            $array = [
                'image' => $image,
                "name" => $request->name,
                'business_id' => auth()->user()->business_id
            ];
            $data->create($array);

            DB::commit();
            return $this->response(true, 'Berhasil menambahkan data', $data);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result = ProductCategory::with('business')->where('id', $id)->first();
        return view('dashboard.product-categories.show', [
            'result' => $result,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $result = ProductCategory::with('business')->where('id', $id)->first();
        return view('dashboard.product-categories.edit', [
            'result' => $result,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = ProductCategory::with('business')->where('id', $id)->first();
            $image = $request->image;
            if ($image) {
                $upload = UploadHelper::upload_file($image, 'product_categories', ProductEnum::IMAGE_EXT);

                if ($upload["IsError"] == TRUE) {
                    return $this->response(false, $upload["Message"]);
                }

                $image = $upload["Path"];
            }
            else{
                $image = $data->image;
            }
            $array = [
                'image' => $image,
                "name" => $request->name,
                'business_id' => auth()->user()->business_id
            ];
            $data->update($array);
            DB::commit();
            return $this->response(true, 'Berhasil update data', $data);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::emergency($th->getMessage());

            return $this->response(false, "Terjadi kesalahan saat memproses data");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ProductCategory::destroy($id);
        return redirect()->route('dashboard.product-categories.index');
    }

    /**
     * @param $success
     * @param  null  $message
     * @param  null  $data
     * @param  int  $statusCode
     * @return object
     */
    public function response($success, $message = null, $data = null, int $statusCode = Response::HTTP_OK): object
    {
        return (object) [
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'code' => $statusCode,
        ];
    }
}
