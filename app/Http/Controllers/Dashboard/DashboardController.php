<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $route;
    protected $view;

    public function __construct()
    {
        $this->route = "dashboard.index";
        $this->view = "dashboard.dashboard";
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view($this->view);
    }
}
