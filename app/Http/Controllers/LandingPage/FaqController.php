<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FaqService;

class FaqController extends Controller
{
    protected $route;
    protected $view;
    protected $faqService;

    public function __construct()
    {
        $this->route = "landing-page.faqs.";
        $this->view = "landing-page.faqs.";
        $this->faqService = new FaqService();
    }

    public function index(Request $request){

        $table = $this->faqService->index($request,false);
        if (!$table->success) {
            alert()->error('Gagal', $table->message);
            return redirect()->route('landing-page.home.index')->withInput();
        }
        $table = $table->data;

        $data = [
            'table' => $table
        ];

        return view($this->view."index",$data);
    }
}
