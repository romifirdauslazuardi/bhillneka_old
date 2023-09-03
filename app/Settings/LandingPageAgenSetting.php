<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class LandingPageAgenSetting extends Settings
{
    public $logo;

    public $logo_dark;

    public $title;

    public $keyword;

    public $favicon;

    public $footer;

    public $email;

    public $phone;

    public $location;

    public $instagram;

    public $facebook;

    public $twitter;

    public $description;

    public static function group(): string
    {
        return 'landing_page_agen';
    }
}
