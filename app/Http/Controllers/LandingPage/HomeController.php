<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\WhyUsService;
use App\Services\TestimonialService;
use App\Services\FaqService;
use App\Services\OurServiceService;

class HomeController extends Controller
{
    protected $route;
    protected $view;
    protected $whyUsService;
    protected $testimonialService;
    protected $faqService;
    protected $ourServiceService;

    public function __construct()
    {
        $this->route = "landing-page.home.";
        $this->view = "landing-page.home.";
        $this->whyUsService = new WhyUsService();
        $this->testimonialService = new TestimonialService();
        $this->faqService = new FaqService();
        $this->ourServiceService = new OurServiceService();
    }

    public function index(Request $request){
        $whyUs = $this->whyUsService->index($request,false);
        $whyUs = $whyUs->data;

        $testimonials = $this->testimonialService->index($request,false);
        $testimonials = $testimonials->data;

        $faqs = $this->faqService->index($request,false);
        $faqs = $faqs->data;

        $our_services = $this->ourServiceService->index($request,false);
        $our_services = $our_services->data;

        $data = [
            'whyUs' => $whyUs,
            'testimonials' => $testimonials,
            'faqs' => $faqs,
            'our_services' => $our_services,
        ];

        return view($this->view."index",$data);
    }
}
