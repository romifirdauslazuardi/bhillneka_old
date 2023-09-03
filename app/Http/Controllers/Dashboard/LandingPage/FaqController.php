<?php

namespace App\Http\Controllers\Dashboard\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Faq\StoreRequest;
use App\Http\Requests\Faq\UpdateRequest;
use App\Services\FaqService;
use Log;

class FaqController extends Controller
{
    protected $route;
    protected $view;
    protected $faqService;

    public function __construct()
    {
        $this->route = "dashboard.landing-page.faqs.";
        $this->view = "dashboard.landing-page.faqs.";
        $this->faqService = new FaqService();
    }

    public function index(Request $request)
    {
        $response = $this->faqService->index($request);

        $data = [
            'table' => $response->data,
        ];

        return view($this->view . 'index', $data);
    }

    public function store(StoreRequest $request)
    {
        try {
            $response = $this->faqService->store($request);
            if (!$response->success) {
                return ResponseHelper::apiResponse(false, $response->message , null, null, $response->code);
            }

            return ResponseHelper::apiResponse(true, $response->message , $response->data , null, $response->code);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return ResponseHelper::apiResponse(false, $th->getMessage() , null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $response = $this->faqService->update($request, $id);
            if (!$response->success) {
                return ResponseHelper::apiResponse(false, $response->message , null, null, $response->code);
            }

            return ResponseHelper::apiResponse(true, $response->message , $response->data , null, $response->code);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return ResponseHelper::apiResponse(false, $th->getMessage() , null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $response = $this->faqService->delete($id);
            if (!$response->success) {
                alert()->error('Gagal', $response->message);
                return redirect()->route($this->route . 'index')->withInput();
            }

            alert()->html('Berhasil', $response->message, 'success');
            return redirect()->route($this->route . 'index');
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            alert()->error('Gagal', $th->getMessage());
            return redirect()->route($this->route . 'index')->withInput();
        }
    }
}
