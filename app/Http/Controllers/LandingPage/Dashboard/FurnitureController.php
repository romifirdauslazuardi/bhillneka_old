<?php

namespace App\Http\Controllers\LandingPage\Dashboard;

use App\Models\Business;
use App\Models\AboutSetting;
use Illuminate\Http\Request;
use App\Models\HeaderSetting;
use App\Models\BusinessCategory;
use App\Models\TestimoniSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use RealRashid\SweetAlert\Facades\Alert;

class FurnitureController extends Controller
{
    public function index()
    {
        $businessData = Business::where('id', auth()->user()->business_id)
            ->with('category')
            ->first();
        if (!$businessData) {
            return redirect()->route('dashboard.auth.login.index');
        }
        $businessCategory = BusinessCategory::where('id', $businessData->category->id)
            ->with('template')
            ->first();
        $route = 'landing-page.landing-page-agen.' . $businessCategory->template->name . '.index';

        if (Route::currentRouteName() != $route) {
            return redirect()->route($route);
        } else {
            $routeAbout = 'landing-page.landing-page-agen.' . $businessCategory->template->name . '.about';
            $routeHeader = 'landing-page.landing-page-agen.' . $businessCategory->template->name . '.header';
            $routeTestimoni = 'landing-page.landing-page-agen.' . $businessCategory->template->name . '.testimoni';
            $dataHeader = HeaderSetting::where('business_id', $businessData->id)->get();
            $dataAbout = AboutSetting::where('business_id', $businessData->id)->first()?->toArray();
            $dataTestimoni = TestimoniSetting::where('business_id', $businessData->id)->get();
            // dd($dataAbout);
            $formTestimoni = makeForm('testimoni_settings');
            $formHeader = makeForm('header_settings');
            $formAbout = makeForm('about_settings', $dataAbout);
            // dd($formAbout);
            $tableTestimoni = getTable('testimoni_settings');
            $tableHeader = getTable('header_settings');
            $data = [
                "title" => "Landing Page " . ucfirst($businessCategory->name),
                'businessCategory' => $businessCategory,
                'routeTestimoni' => $routeTestimoni,
                'routeHeader' => $routeHeader,
                'routeAbout' => $routeAbout,
                'formTestimoni' => $formTestimoni,
                'formHeader' => $formHeader,
                'formAbout' => $formAbout,
                'tableTestimoni' => $tableTestimoni,
                'tableHeader' => $tableHeader,
                'dataTestimoni' => $dataTestimoni,
                'dataHeader' => $dataHeader,
            ];
            return view('dashboard.furniture.index', $data);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function about(Request $request)
    {
        $businessData = Business::where('id', auth()->user()->business_id)
            ->with('category')
            ->first();
        $businessCategory = BusinessCategory::where('id', $businessData->category->id)
            ->with('template')
            ->first();
        $route = 'landing-page.landing-page-agen.' . $businessCategory->template->name . '.index';
        $array = getArrayPost($request, 'about_settings');
        $data = AboutSetting::where('business_id', $businessData->id)->first();
        if ($data) {
            $data->update($array);
        } else {
            AboutSetting::create($array);
        }
        return redirect()->to(route($route));
    }

    public function testimoni(Request $request)
    {
        $businessData = Business::where('id', auth()->user()->business_id)
            ->with('category')
            ->first();
        $businessCategory = BusinessCategory::where('id', $businessData->category->id)
            ->with('template')
            ->first();
        $route = 'landing-page.landing-page-agen.' . $businessCategory->template->name . '.index';
        $array = getArrayPost($request, 'testimoni_settings');
        TestimoniSetting::create($array);
        return redirect()->to(route($route));
    }

    public function testimoniShow($id)
    {
        $data = TestimoniSetting::find($id);
        return response()->json($data);
    }

    public function headerShow($id)
    {
        $data = HeaderSetting::find($id);
        return response()->json($data);
    }

    public function testimoniDelete($id)
    {
        TestimoniSetting::destroy($id);
        Alert::toast("Data Berhasil dihapus", 'error');
        return redirect()->back();
    }

    public function headerDelete($id)
    {
        HeaderSetting::destroy($id);
        Alert::toast("Data Berhasil dihapus", 'error');
        return redirect()->back();
    }

    public function testimoniUpdate(Request $request, $id)
    {
        $data = TestimoniSetting::find($id);
        $array = getArrayPost($request, 'testimoni_settings');
        if (is_array($array)) {
            $data->update($array);
        }
        return redirect()->back();
    }

    public function headerUpdate(Request $request, $id)
    {
        $data = HeaderSetting::find($id);
        $array = getArrayPost($request, 'header_settings');
        if (is_array($array)) {
            $data->update($array);
        }
        return redirect()->back();
    }

    public function header(Request $request)
    {
        $businessData = Business::where('id', auth()->user()->business_id)
            ->with('category')
            ->first();
        $businessCategory = BusinessCategory::where('id', $businessData->category->id)
            ->with('template')
            ->first();
        $route = 'landing-page.landing-page-agen.' . $businessCategory->template->name . '.index';
        $array = getArrayPost($request, 'header_settings');
        if (is_array($array)) {
            HeaderSetting::create($array);
        }
        return redirect()->to(route($route));
    }
}
