<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OurServiceService;
use App\Traits\HasSeo;

class OurServiceController extends Controller
{
    use HasSeo;

    protected $route;
    protected $view;
    protected $ourServiceService;

    public function __construct()
    {
        $this->route = "landing-page.our-services.";
        $this->view = "landing-page.our-services.";
        $this->ourServiceService = new OurServiceService();
    }

    public function index(Request $request){

        $table = $this->ourServiceService->index($request,false);
        if (!$table->success) {
            alert()->error('Gagal', $table->message);
            return redirect()->route('landing-page.home.index')->withInput();
        }
        $table = $table->data;

        $this->seo(
            title: "Our Service",
        );

        $data = [
            'table' => $table
        ];

        return view($this->view."index",$data);
    }
}
