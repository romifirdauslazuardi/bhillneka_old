<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Requests\LandingPage\Contact\TicketRequest;
use App\Mail\TicketMail;
use App\Settings\LandingPageSetting;
use Mail;
use Log;

class ContactUsController extends Controller
{
    protected $route;
    protected $view;

    public function __construct()
    {
        $this->route = "landing-page.contact-us.";
        $this->view = "landing-page.contact-us.";
    }

    public function index(){
        return view($this->view."index");
    }

    public function store(TicketRequest $request,LandingPageSetting $landingPageSetting)
    {
        try {

            $name = (empty($request->name)) ? null : trim(strip_tags($request->name));
            $email = (empty($request->email)) ? null : trim(strip_tags($request->email));
            $message = (empty($request->message)) ? null : trim(strip_tags($request->message));
            $subject = (empty($request->subject)) ? null : trim(strip_tags($request->subject));
            $adminEmail = $landingPageSetting->email;

            if($adminEmail){
                Mail::to($adminEmail)->send(new TicketMail($email, $message, $name,$subject));
            }

            alert()->html('Berhasil','Ticket berhasil dikirimkan','success'); 
            return redirect()->route($this->route."index");

        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());
            alert()->html("Gagal",$e->getMessage(), 'error');
            return redirect()->back()->with("error",$e->getMessage())->withInput();
        }
    }
}
