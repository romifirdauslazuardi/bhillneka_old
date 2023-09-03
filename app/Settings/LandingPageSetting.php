<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class LandingPageSetting extends Settings
{
    public ?string $logo;

    public ?string $logo_dark;

    public ?string $title;

    public ?string $keyword;

    public ?string $favicon;

    public ?string $footer;

    public ?string $email;

    public ?string $phone;

    public ?string $location;

    public ?string $instagram;

    public ?string $facebook;

    public ?string $twitter;

    public ?string $description;

    public static function group(): string
    {
        return 'landing_page';
    }
}
