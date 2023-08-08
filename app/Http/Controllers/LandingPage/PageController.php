<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PageService;
use App\Traits\HasSeo;

class PageController extends Controller
{
    use HasSeo;

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

        $this->seo(
            title: $result->name,
            description: $result->trixRender("content")
        );

        $data = [
            'result' => $result
        ];

        return view($this->view."index",$data);
    }
}
