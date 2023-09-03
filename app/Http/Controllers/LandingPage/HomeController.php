<?php

namespace App\Http\Controllers\LandingPage;

use App\Helpers\SettingHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\WhyUsService;
use App\Services\TestimonialService;
use App\Services\FaqService;
use App\Services\OurServiceService;
use App\Services\DashboardService;
use App\Services\PartnerService;
use App\Traits\HasSeo;

class HomeController extends Controller
{
    use HasSeo;

    protected $route;
    protected $view;
    protected $whyUsService;
    protected $testimonialService;
    protected $faqService;
    protected $ourServiceService;
    protected $dashboardService;
    protected $partnerService;

    public function __construct()
    {
        $this->route = "landing-page.home.";
        $this->view = "landing-page.home.";
        $this->whyUsService = new WhyUsService();
        $this->testimonialService = new TestimonialService();
        $this->faqService = new FaqService();
        $this->ourServiceService = new OurServiceService();
        $this->dashboardService = new DashboardService();
        $this->partnerService = new PartnerService();
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

        $partners = $this->partnerService->index($request,false);
        $partners = $partners->data;

        $totalVisitor = $this->dashboardService->totalVisitor();
        
        $totalOrderSuccess = $this->dashboardService->totalOrderSuccess();

        $this->seo(
            title: SettingHelper::settings("landing_page", "title"),
            description: SettingHelper::settings("landing_page", "description"),
            keywords: SettingHelper::settings("landing_page", "keyword"),
            url: null,
            image: SettingHelper::settings("landing_page", "logo")
        );

        $data = [
            'whyUs' => $whyUs,
            'testimonials' => $testimonials,
            'faqs' => $faqs,
            'our_services' => $our_services,
            'partners' => $partners,
            'totalVisitor' => $totalVisitor,
            'totalOrderSuccess' => $totalOrderSuccess,
        ];

        return view($this->view."index",$data);
    }
}
