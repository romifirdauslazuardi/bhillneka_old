<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use App\Http\Requests\Setting\LandingpageSettingRequest;
use App\Settings\LandingPageSetting;
use App\Helpers\UploadHelper;
use App\Helpers\ResponseHelper;
use App\Enums\Setting\LandingPageSettingEnum;

class LandingPageSettingController extends Controller
{
    protected $route;
    protected $view;

    public function __construct()
    {
        $this->route = "dashboard.settings.landing-page.";
        $this->view = "dashboard.settings.landing-page";
    }

    public function index(LandingPageSetting $landingPageSetting)
    {
        $data = [
            'result' => $landingPageSetting,
        ];

        return view($this->view, $data);
    }

    public function update(LandingpageSettingRequest $request)
    {
        try {
            $title = $request->title;
            $description = $request->description;
            $email = $request->email;
            $phone = $request->phone;
            $location = $request->location;
            $instagram = $request->instagram;
            $facebook = $request->facebook;
            $twitter = $request->twitter;
            $footer = $request->footer;
            $logo = $request->file("logo");
            $logo_dark = $request->file("logo_dark");

            if ($logo) {
                $upload = UploadHelper::upload_file($logo, 'settings/landing-page', LandingPageSettingEnum::LOGO_EXT);

                if ($upload["IsError"] == TRUE) {
                    return ResponseHelper::apiResponse(false, $upload["Message"] , null, null, 422);
                }

                $logo = $upload["Path"];
            }

            if ($logo_dark) {
                $upload = UploadHelper::upload_file($logo_dark, 'settings/landing-page', LandingPageSettingEnum::LOGO_EXT);

                if ($upload["IsError"] == TRUE) {
                    return ResponseHelper::apiResponse(false, $upload["Message"] , null, null, 422);
                }

                $logo_dark = $upload["Path"];
            }

            $landingPageSetting = new LandingPageSetting();
            if ($logo) {
                $landingPageSetting->logo = $logo;
            }
            if ($logo_dark) {
                $landingPageSetting->logo_dark = $logo_dark;
            }
            $landingPageSetting->title = $title;
            $landingPageSetting->description = $description;
            $landingPageSetting->email = $email;
            $landingPageSetting->phone = $phone;
            $landingPageSetting->location = $location;
            $landingPageSetting->instagram = $instagram;
            $landingPageSetting->facebook = $facebook;
            $landingPageSetting->twitter = $twitter;
            $landingPageSetting->footer = $footer;
            $landingPageSetting->save();

            return ResponseHelper::apiResponse(true, "Pengaturan landing page berhasil diubah" , $landingPageSetting , null, 200);

        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return ResponseHelper::apiResponse(false, $th->getMessage() , null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
