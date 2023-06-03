<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use App\Http\Requests\Setting\DashboardSettingRequest;
use App\Settings\DashboardSetting;
use App\Helpers\UploadHelper;
use App\Helpers\ResponseHelper;
use App\Enums\Setting\DashboardSettingEnum;

class DashboardSettingController extends Controller
{
    protected $route;
    protected $view;

    public function __construct()
    {
        $this->route = "dashboard.settings.dashboard.";
        $this->view = "dashboard.settings.dashboard";
    }

    public function index(DashboardSetting $dashboardSetting)
    {
        $data = [
            'result' => $dashboardSetting,
        ];

        return view($this->view, $data);
    }

    public function update(DashboardSettingRequest $request)
    {
        try {
            $title = $request->title;
            $footer = $request->footer;
            $logo = $request->file("logo");
            $logo_dark = $request->file("logo_dark");
            $logo_icon = $request->file("logo_icon");

            if ($logo) {
                $upload = UploadHelper::upload_file($logo, 'settings/dashboard', DashboardSettingEnum::LOGO_EXT);

                if ($upload["IsError"] == TRUE) {
                    return ResponseHelper::apiResponse(false, $upload["Message"] , null, null, 422);
                }

                $logo = $upload["Path"];
            }

            if ($logo_dark) {
                $upload = UploadHelper::upload_file($logo_dark, 'settings/dashboard', DashboardSettingEnum::LOGO_EXT);

                if ($upload["IsError"] == TRUE) {
                    return ResponseHelper::apiResponse(false, $upload["Message"] , null, null, 422);
                }

                $logo_dark = $upload["Path"];
            }

            if ($logo_icon) {
                $upload = UploadHelper::upload_file($logo_icon, 'settings/dashboard', DashboardSettingEnum::LOGO_EXT);

                if ($upload["IsError"] == TRUE) {
                    return ResponseHelper::apiResponse(false, $upload["Message"] , null, null, 422);
                }

                $logo_icon = $upload["Path"];
            }

            $dashboardSetting = new DashboardSetting();
            if ($logo) {
                $dashboardSetting->logo = $logo;
            }
            if ($logo_dark) {
                $dashboardSetting->logo_dark = $logo_dark;
            }
            if ($logo_icon) {
                $dashboardSetting->logo_icon = $logo_icon;
            }
            $dashboardSetting->title = $title;
            $dashboardSetting->footer = $footer;
            $dashboardSetting->save();

            return ResponseHelper::apiResponse(true, "Pengaturan dashboard berhasil diubah" , $dashboardSetting , null, 200);

        } catch (\Throwable $th) {
            Log::emergency($th->getMessage());

            return ResponseHelper::apiResponse(false, $th->getMessage() , null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
