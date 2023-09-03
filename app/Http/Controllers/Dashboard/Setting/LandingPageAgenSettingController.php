<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use App\Http\Requests\Setting\LandingPageSettingRequest;
use App\Settings\LandingPageAgenSetting;
use App\Helpers\UploadHelper;
use App\Helpers\ResponseHelper;
use App\Enums\Setting\LandingPageSettingEnum;
use App\Models\Business;
use App\Models\LandingAgen;

class LandingPageAgenSettingController extends Controller
{
    protected $route;
    protected $view;

    public function __construct()
    {
        $this->route = "dashboard.settings.landing-page-agen.";
        $this->view = "dashboard.settings.landing-page-agen";
    }

    public function index(LandingAgen $landingAgen)
    {
        $business_id = auth()->user()->business_id;
        $data = [
            'result' => $landingAgen->where('business_id', $business_id)->first()
        ];

        // dd($landingAgen);

        return view($this->view, $data);
    }

    public function update(LandingPageSettingRequest $request)
    {
        try {
            $title = $request->title;
            $description = $request->description;
            $keyword = $request->keyword;
            $email = $request->email;
            $phone = $request->phone;
            $location = $request->location;
            $instagram = $request->instagram;
            $facebook = $request->facebook;
            $twitter = $request->twitter;
            $footer = $request->footer;
            $logo = $request->file("logo");
            $logo_dark = $request->file("logo_dark");
            $favicon = $request->file("favicon");
            $business_id = auth()->user()->business_id;
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

            if ($favicon) {
                $upload = UploadHelper::upload_file($favicon, 'settings/landing-page', LandingPageSettingEnum::LOGO_EXT);

                if ($upload["IsError"] == TRUE) {
                    return ResponseHelper::apiResponse(false, $upload["Message"] , null, null, 422);
                }

                $favicon = $upload["Path"];
            }
            $array = [];

            if ($logo) {
                $array['logo'] = $logo;
            }
            if ($logo_dark) {
                $array['logo_dark'] = $logo_dark;
            }
            if ($favicon) {
                $array['favicon'] = $favicon;
            }
            $array['title'] = $title;
            $array['description'] = $description;
            $array['keyword'] = $keyword;
            $array['email'] = $email;
            $array['phone'] = $phone;
            $array['location'] = $location;
            $array['instagram'] = $instagram;
            $array['facebook'] = $facebook;
            $array['twitter'] = $twitter;
            $array['footer'] = $footer;
            $array['business_id'] = $business_id;

            $landingPageSetting = LandingAgen::where('business_id', $business_id)->first();
            if($landingPageSetting == null){
                $landingPageSetting = new LandingAgen();
                $landingPageSetting->create($array);
            }
            else{
                $landingPageSetting->update($array);
            }

            return ResponseHelper::apiResponse(true, "Pengaturan landing page berhasil diubah" , $landingPageSetting , null, 200);

        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return ResponseHelper::apiResponse(false, $th->getMessage() , null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
