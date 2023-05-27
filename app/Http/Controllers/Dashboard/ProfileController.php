<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Profile\UpdateRequest;
use App\Services\ProfileService;
use Auth;
use Log;

class ProfileController extends Controller
{
    protected $route;
    protected $view;
    protected $profileService;

    public function __construct()
    {
        $this->route = "dashboard.profile.";
        $this->view = "dashboard.profile";
        $this->profileService = new ProfileService();
    }

    public function index()
    {
        $user = Auth::user();

        $data = [
            'result' => $user
        ];

        return view($this->view, $data);
    }

    public function update(UpdateRequest $request)
    {
        try {
            $response = $this->profileService->update($request);
            if (!$response->success) {
                return ResponseHelper::apiResponse(false, $response->message , null, null, $response->code);
            }

            return ResponseHelper::apiResponse(true, $response->message , $response->data , null, $response->code);
        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return ResponseHelper::apiResponse(false, $th->getMessage() , null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
