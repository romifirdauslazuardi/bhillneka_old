<?php

namespace App\Http\Controllers\Dashboard\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

class GoogleAnalyticController extends Controller
{
    protected $route;
    protected $view;

    public function __construct()
    {
        $this->route = "dashboard.landing-page.google-analytics.";
        $this->view = "dashboard.landing-page.google-analytics.";
    }

    public function index(Request $request)
    {
        
        $table = Analytics::fetchVisitorsAndPageViews(Period::months(6));

        $data = [
            'table' => $table,
        ];

        return view($this->view . 'index', $data);
    }
}
