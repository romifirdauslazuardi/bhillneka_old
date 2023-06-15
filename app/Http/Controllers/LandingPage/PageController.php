<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PageService;

class PageController extends Controller
{
    protected $route;
    protected $view;
    protected $pageService;

    public function __construct()
    {
        $this->route = "landing-page.pages.";
        $this->view = "landing-page.pages.";
        $this->pageService = new PageService();
    }

    public function index($slug){
        $result = $this->pageService->showBySlug($slug);
        if (!$result->success) {
            alert()->error('Gagal', $result->message);
            return redirect()->route('landing-page.home.index')->withInput();
        }
        $result = $result->data;

        $data = [
            'result' => $result
        ];

        return view($this->view."index",$data);
    }
}
